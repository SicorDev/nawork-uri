<?php

require_once 'lib/class.tx_naworkuri_transformer.php';

class tx_naworkuri implements t3lib_Singleton {

	protected $redirectUrl = NULL;

	/**
	 * decode uri and extract parameters
	 *
	 * @param unknown_type $params
	 * @param TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $ref
	 */
	function uri2params($params, $ref) {
		global $TYPO3_CONF_VARS;

		if ($params['pObj']->siteScript && substr($params['pObj']->siteScript, 0, 9) != 'index.php' && substr($params['pObj']->siteScript, 0, 1) != '?') {
			$uri = $params['pObj']->siteScript;
			list($uri, $parameters) = t3lib_div::trimExplode('?', $uri);
			// translate uri
			$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['nawork_uri']);
			/* @var $configReader tx_naworkuri_configReader */
			$configReader = t3lib_div::makeInstance('tx_naworkuri_configReader', $extConf['XMLPATH']);
			/* @var $translator tx_naworkuri_transformer */
			$translator = t3lib_div::makeInstance('tx_naworkuri_transformer', $configReader, $extConf['MULTIDOMAIN']);
			try {
				$uri_params = $translator->uri2params($uri);
				/* should the path be converted to lowercase to treat uppercase paths like normal paths */
				if (($configReader->getCheckForUpperCaseURI() && $uri == strtolower($uri)) || !$configReader->getCheckForUpperCaseURI()) {
					if (is_array($uri_params)) { // uri found
						$params['pObj']->id = $uri_params['id'];
						unset($uri_params['id']);
						$params['pObj']->mergingWithGetVars($uri_params);
					} else { // handle 404
						$this->handlePagenotfound(array('currentUrl' => $ref->siteScript, 'reaseonText' => 'The requested path could not be found', 'pageAccessFailureReasons' => array()), $ref);
					}
				}
			} catch (Tx_NaworkUri_Exception_UrlIsRedirectException $ex) {
				$url = $ex->getUrl();
				if ($url['type'] == tx_naworkuri_cache::TX_NAWORKURI_URI_TYPE_OLD) {
					/*
					 * we must not redirect here because we cannot use link generation here to get the correct target path
					 */
					$this->redirectUrl = $url;
				} elseif ($url['type'] == tx_naworkuri_cache::TX_NAWORKURI_URI_TYPE_REDIRECT) {
					/* add hook for pre processing the redirect record */
					if (is_array($TYPO3_CONF_VARS['SC_OPTIONS']['tx_naworkuri']['redirect-preProcess'])) {
						foreach ($TYPO3_CONF_VARS['SC_OPTIONS']['tx_naworkuri']['redirect-preProcess'] as $funcRef) {
							t3lib_div::callUserFunction($funcRef, $url, $this);
						}
					}
					$newUrl = parse_url($url['redirect_path']);
					$requestUrl = parse_url(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
					if (empty($newUrl['scheme']))
						$newUrl['scheme'] = $requestUrl['scheme'];
					if (empty($newUrl['host']))
						$newUrl['host'] = $requestUrl['host'];
					if (substr($newUrl['path'], 0, 1) != '/')
						$newUrl['path'] = '/' . $newUrl['path'];
					$uri = $newUrl['scheme'] . '://' . $newUrl['host'] . $newUrl['path'];
					$queryParams = tx_naworkuri_helper::explode_parameters($newUrl['query']); // use only query string from redirect target and discard the given one
					if (!empty($queryParams)) {
						$uri .= '?' . tx_naworkuri_helper::implode_parameters($queryParams);
					}
					if (array_key_exists('fragment', $newUrl) && !empty($newUrl['fragment'])) {
						$uri .= '#' . $newUrl['fragment'];
					}
					tx_naworkuri_helper::sendRedirect($uri, $url['redirect_mode']);
				}
			}
		}
	}

	/**
	 * This function takes the link config and the tsfe as arguments and initializes the conversion of
	 * the totalURL to a path
	 *
	 * @param array $link
	 * @param tslib_fe $ref
	 */
	function params2uri(&$link, $ref) {
		global $TYPO3_CONF_VARS;
		if ($GLOBALS['TSFE']->config['config']['tx_naworkuri.']['enable'] == 1 && $link['LD']['url']) {
			list($path, $params) = explode('?', $link['LD']['totalURL']);
			$params = rawurldecode(html_entity_decode($params));
			$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['nawork_uri']);
			$configReader = t3lib_div::makeInstance('tx_naworkuri_configReader', $extConf['XMLPATH']);
			$translator = t3lib_div::makeInstance('tx_naworkuri_transformer', $configReader, (boolean) $extConf['MULTIDOMAIN']);
			try {
				$url = $translator->params2uri($params);
				$link['LD']['totalURL'] = tx_naworkuri_helper::finalizeUrl($url);
				/* add hook for post processing the url */
				if (is_array($TYPO3_CONF_VARS['SC_OPTIONS']['tx_naworkuri']['url-postProcess'])) {
					$hookParams = array(
						'url' => $url,
						'params' => tx_naworkuri_helper::explode_parameters($params),
						'LD' => $link['LD']
					);
					foreach ($TYPO3_CONF_VARS['SC_OPTIONS']['tx_naworkuri']['url-postProcess'] as $funcRef) {
						t3lib_div::callUserFunction($funcRef, $hookParams, $this);
					}
					if ($hookParams['url'] !== FALSE) { // if the url is not false set it
						$link['LD']['totalURL'] = $hookParams['url'];
					}
				}
				if (!preg_match('/https?:\/\//', $link['LD']['totalURL']) && !empty($GLOBALS['TSFE']->config['config']['absRefPrefix'])) {
					if (substr($link['LD']['totalURL'], 0, strlen($GLOBALS['TSFE']->config['config']['absRefPrefix'])) != $GLOBALS['TSFE']->config['config']['absRefPrefix']) {
						$link['LD']['totalURL'] = $GLOBALS['TSFE']->config['config']['absRefPrefix'] . $link['LD']['totalURL'];
					}
				}
			} catch (Tx_NaworkUri_Exception_UrlIsNotUniqueException $ex) {
				/* log unique failure to belog */
				tx_naworkuri_helper::log('Url "' . $ex->getPath() . ' is not unique with parameters ' . tx_naworkuri_helper::implode_parameters($ex->getParameters()), tx_naworkuri_helper::LOG_SEVERITY_ERROR);
				$totalURL = 'index.php';
				if (!empty($params)) {
					$totalURL .= '?' . tx_naworkuri_helper::implode_parameters(tx_naworkuri_helper::explode_parameters($params));
				}
				$totalURL = tx_naworkuri_helper::finalizeUrl($totalURL);
				$link['LD']['totalURL'] = $totalURL;
			} catch (Tx_NaworkUri_Exception_DbErrorException $ex) {
				/* log db errors to belog */
				tx_naworkuri_helper::log('An database error occured while creating a url. The SQL error was: "' . $ex->getSqlError() . '"', tx_naworkuri_helper::LOG_SEVERITY_ERROR);
				$totalURL = 'index.php';
				if (!empty($params)) {
					$totalURL .= '?' . tx_naworkuri_helper::implode_parameters(tx_naworkuri_helper::explode_parameters($params));
				}
				$totalURL = tx_naworkuri_helper::finalizeUrl($totalURL);
				$link['LD']['totalURL'] = $totalURL;
			}
		}
	}

	/**
	 * This function is used for two purposes. The first purpose is to redirect if the page is called via parameterized
	 * form, like "index.php?id=...", to the path form. The second purpose is to redirect if the type or L parameter
	 * are not valid, e.g. the type parameter contains "%25252525252523header" or something other non useful content.
	 *
	 * The first type only happens if the site is called via 'index.php?id=...' or '?id=...'
	 * The second type of redirect is sent if the parameters are checked and not seen as valid.
	 *
	 * Whatever redirect is sent, the state of enable and redirect option of nawork_uri in config are checked. Additionally
	 * it is checked that the page is not called as preview from admin panel and there is a sitescript at all.
	 *
	 * @param unknown_type $params
	 * @param tslib_fe $ref
	 */
	function redirect2uri($params, $ref) {
		global $TYPO3_CONF_VARS;
		/*
		 * if we set a redirectUrl above because an old url was called we should
		 * redirect it here because at this point we have the full tsfe to get
		 * the correct target url
		 */
		if ($this->redirectUrl != NULL) {
			// translate uri
			$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['nawork_uri']);
			/* @var $configReader tx_naworkuri_configReader */
			$configReader = t3lib_div::makeInstance('tx_naworkuri_configReader', $extConf['XMLPATH']);
			/* @var $translator tx_naworkuri_transformer */
			$translator = t3lib_div::makeInstance('tx_naworkuri_transformer', $configReader, $extConf['MULTIDOMAIN']);
			$newUrlParameters = array('id' => $this->redirectUrl['page_uid'], 'L' => $this->redirectUrl['sys_language_uid']);
			if (!empty($this->redirectUrl['params'])) {
				$newUrlParameters = array_merge($newUrlParameters, tx_naworkuri_helper::explode_parameters($this->redirectUrl['params']));
			}
			$newUrl = $translator->params2uri(tx_naworkuri_helper::implode_parameters($newUrlParameters, FALSE), TRUE, TRUE);
			$newUrl = tx_naworkuri_helper::finalizeUrl($newUrl, TRUE);
			/* parse the current request url and prepend the scheme and host to the url */
			$requestUrl = parse_url(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
			$newUrl = parse_url($newUrl);
			if (empty($newUrl['scheme']))
				$newUrl['scheme'] = $requestUrl['scheme'];
			if (empty($newUrl['host']))
				$newUrl['host'] = $requestUrl['host'];
			if (substr($newUrl['path'], 0, 1) != '/')
				$newUrl['path'] = '/' . $newUrl['path'];
			$uri = $newUrl['scheme'] . '://' . $newUrl['host'] . $newUrl['path'];
			$queryParams = array_merge(tx_naworkuri_helper::explode_parameters(rawurldecode($requestUrl['query'])), tx_naworkuri_helper::explode_parameters($newUrl['query']));
			if (!empty($queryParams)) {
				$uri .= '?' . tx_naworkuri_helper::implode_parameters($queryParams);
			}
			if (array_key_exists('fragment', $newUrl) && !empty($newUrl['fragment'])) {
				$uri .= '#' . $newUrl['fragment'];
			}
			tx_naworkuri_helper::sendRedirect($uri, 301);
		} elseif ($GLOBALS['TSFE']->config['config']['tx_naworkuri.']['enable'] == 1 && empty($_GET['ADMCMD_prev']) && $GLOBALS['TSFE']->config['config']['tx_naworkuri.']['redirect'] == 1 && $GLOBALS['TSFE']->siteScript) {
			list($path, $params) = explode('?', $GLOBALS['TSFE']->siteScript);
			$params = rawurldecode(html_entity_decode($params)); // decode the query string because it is expected by the further processing functions
			$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['nawork_uri']);
			$configReader = t3lib_div::makeInstance('tx_naworkuri_configReader', $extConf['XMLPATH']);
			$translator = t3lib_div::makeInstance('tx_naworkuri_transformer', $configReader, $extConf['MULTIDOMAIN']);
			$tempParams = tx_naworkuri_helper::explode_parameters($params);

			/* should the path be converted to lowercase to treat uppercase paths like normal paths */
			if ($configReader->getCheckForUpperCaseURI()) {
				if ($path != strtolower($path)) {
					$uri = $GLOBALS['TSFE']->config['config']['baseURL'] . strtolower($path);
					if (empty($uri)) {
						$uri = '/';
					}
					if (!empty($params)) {
						$uri .= '?' . $params;
					}
					header('Location: ' . $uri, true, $configReader->getRedirectStatus());
					exit;
				}
			}

			/* check if type should be casted to int to avoid strange behavior when creating links */
			if ($configReader->getCastTypeToInt()) {
				$type = !empty($tempParams['type']) ? $tempParams['type'] : t3lib_div::_GP('type');
				if (!empty($type) && !tx_naworkuri_helper::canBeInterpretedAsInteger($type)) { // if type is not an int
					unset($tempParams['type']); // unset type param to use system default
					/* should we redirect if the parameter is wrong */
					if ($configReader->getRedirectOnParameterDiff()) {
						$uri = $GLOBALS['TSFE']->config['config']['baseURL'] . $path;
						if (empty($uri)) {
							$uri = '/';
						}
						if (count($tempParams) > 0) {
							$uri .= '?' . tx_naworkuri_helper::implode_parameters($tempParams);
						}
						header('Location: ' . $uri, true, $configReader->getRedirectStatus());
						exit;
					}
				}
			}

			/* check if L should be casted to int to avoid strange behavior when creating links */
			if ($configReader->getCastLToInt()) {
				$L = !empty($tempParams['L']) ? $tempParams['L'] : t3lib_div::_GP('L');
				if (!empty($L) && !tx_naworkuri_helper::canBeInterpretedAsInteger($L)) { // if L is not an int
					unset($tempParams['L']); // unset L param to use system default
					/* should we redirect if the parameter is wrong */
					if ($configReader->getRedirectOnParameterDiff()) {
						$uri = $GLOBALS['TSFE']->config['config']['baseURL'] . $path;
						if (empty($uri)) {
							$uri = '/';
						}
						if (count($tempParams) > 0) {
							$uri .= '?' . tx_naworkuri_helper::implode_parameters($tempParams);
						}
						header('Location: ' . $uri, true, $configReader->getRedirectStatus());
						exit;
					}
				}
			}

			/* if the page is called via parameterized form look for a path to redirect to */
			if ((substr($GLOBALS['TSFE']->siteScript, 0, 9) == 'index.php' || substr($GLOBALS['TSFE']->siteScript, 0, 1) == '?')) {
				$dontCreateNewUrls = true;
				$tempParams = tx_naworkuri_helper::explode_parameters($params);
				if ((count($tempParams) < 3 && array_key_exists('L', $tempParams) && array_key_exists('id', $tempParams)) || (count($tempParams) < 2 && array_key_exists('id', $tempParams))) {
					if (tx_naworkuri_helper::isActiveBeUserSession()) {
						$dontCreateNewUrls = false;
					}
				}
				$ignoreTimeout = true;
				try {
					$uri = $translator->params2uri($params, $dontCreateNewUrls, $ignoreTimeout);
					if ($_SERVER['REQUEST_METHOD'] == 'GET' && ($path == 'index.php' || $path == '') && $uri !== false && $uri != $GLOBALS['TSFE']->siteScript) {
						$uri = tx_naworkuri_helper::finalizeUrl($uri, TRUE); // TRUE is for redirect, this applies "/" by default and the baseURL if set
						tx_naworkuri_helper::sendRedirect($uri, $configReader->getRedirectStatus());
						exit;
					}
				} catch (Tx_NaworkUri_Exception_UrlIsNotUniqueException $ex) {
					/* log unique failure to belog */
					tx_naworkuri_helper::log('Url "' . $ex->getPath() . ' is not unique with parameters ' . tx_naworkuri_helper::implode_parameters($ex->getParameters()), tx_naworkuri_helper::LOG_SEVERITY_ERROR);
				} catch (Tx_NaworkUri_Exception_DbErrorException $ex) {
					/* log db errors to belog */
					tx_naworkuri_helper::log('An database error occured while creating a url. The SQL error was: "' . $ex->getSqlError() . '"', tx_naworkuri_helper::LOG_SEVERITY_ERROR);
				}
			}
		}
	}

	/**
	 * Update the md5 values automatically
	 *
	 * @param unknown_type $incomingFieldArray
	 * @param unknown_type $table
	 * @param unknown_type $id
	 * @param unknown_type $res
	 */
	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, &$table, &$id, &$res) {
		if ($table == "tx_naworkuri_uri") {
			if ($incomingFieldArray['path'] || $incomingFieldArray['path'] == '')
				$incomingFieldArray['hash_path'] = md5($incomingFieldArray['path']);
			if ($incomingFieldArray['params'] || $incomingFieldArray['params'] == '')
				$incomingFieldArray['hash_params'] = md5($incomingFieldArray['params']);
		}
	}

	function curl_exec_follow($ch, &$maxredirect = null) {
		$mr = $maxredirect === null ? 5 : intval($maxredirect);
		if (ini_get('open_basedir') == '' && ini_get('safe_mode') == '') {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
		} else {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			if ($mr > 0) {
				$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

				$rch = curl_copy_handle($ch);
				curl_setopt($rch, CURLOPT_HEADER, true);
				curl_setopt($rch, CURLOPT_NOBODY, true);
				curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
				curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
				do {
					curl_setopt($rch, CURLOPT_URL, $newurl);
					$header = curl_exec($rch);
					if (curl_errno($rch)) {
						$code = 0;
					} else {
						$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
						if ($code == 301 || $code == 302) {
							preg_match('/Location:(.*?)\n/', $header, $matches);
							$newurl = trim(array_pop($matches));
						} else {
							$code = 0;
						}
					}
				} while ($code && --$mr);
				curl_close($rch);
				if (!$mr) {
					if ($maxredirect === null) {
						trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
					} else {
						$maxredirect = 0;
					}
					return false;
				}
				curl_setopt($ch, CURLOPT_URL, $newurl);
			}
		}
		return curl_exec($ch);
	}

	/**
	 * Handles the pagenotfound event:
	 * This function is called from tx_naworkuri_uri::uri2params if the path is not found.
	 * Additionally it can be used as a user function in $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling'], e.g.:
	 * USER_FUNCTION:EXT:nawork_uri/class.tx_naworkuri.php:&tx_naworkuri->handlePagenotfound.
	 * 
	 * Two situations are supported. The page is not found, this is the case, if the path was not found or a
	 * non-existing page id is requested. The other case is, that a page is requested, that is not accessible without being
	 * logged in in the frontend. The handling case can be configured via <pageNotAccessible> tag in the configuration file.
	 * If this tag does not exist that pagenotfound configuration is used. So handling the page being not accessible is
	 * optional behavior.
	 * 
	 * @param array $params
	 * @param TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $frontendController
	 * 
	 * @todo Handle not found and not accessible differently
	 */
	public function handlePagenotfound($params, $frontendController) {
		$output = '';
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nawork_uri']);
		$configReader = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_naworkuri_configReader', $extConf['XMLPATH']);
		/* @var $configReader tx_naworkuri_configReader */
		/* the page is not accessible without being logged in, so handle this, if configured */
		if (array_key_exists('pageAccessFailureReasons', $params) && is_array($params['pageAccessFailureReasons']) && array_key_exists('fe_group', $params['pageAccessFailureReasons']) && $configReader->hasPageNotAccessibleConfiguration()) {
			header($configReader->getPageNotAccessibleConfigurationStatus());
			header('Content-type: text/html; charset=utf8');
			switch ($configReader->getPageNotAccessibleConfigurationBehaviorType()) {
				case 'message':
					$output = $configReader->getPageNotAccessibleConfigurationBehaviorValue();
					break;
				case 'page':
					if (TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('HTTP_USER_AGENT') != 'nawork_uri') {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $configReader->getPageNotAccessibleConfigurationBehaviorValue());
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
						curl_setopt($curl, CURLOPT_TIMEOUT, 30);
						curl_setopt($curl, CURLOPT_USERAGENT, 'nawork_uri');
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
						curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
						curl_setopt($curl, CURLOPT_REFERER, TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
						$output = $this->curl_exec_follow($curl);
					} else {
						$output = '404 not found! The 404 Page URL ' . $configReader->getPageNotAccessibleConfigurationBehaviorValue() . ' seems to cause a loop.';
					}
					break;
				case 'redirect':
					$path = html_entity_decode($configReader->getPageNotAccessibleConfigurationBehaviorValue());
					if (!($_SERVER['REQUEST_METHOD'] == 'POST' && preg_match('/index.php/', $_SERVER['SCRIPT_NAME']))) {
						tx_naworkuri_helper::sendRedirect($path, 301); // send headers and exits
					}
				default:
					$output = '<html><head><title>403 Forbidden</title></head><body><h1>403 Forbidden</h1><p>You don\'t have the permission to access this page</p></body></html>';
			}
		} elseif ($configReader->hasPageNotFoundConfig()) {
			header('Content-Type: text/html; charset=utf-8');
			header($configReader->getPageNotFoundConfigStatus());
			switch ($configReader->getPageNotFoundConfigBehaviorType()) {
				case 'message':
					$output = $configReader->getPageNotFoundConfigBehaviorValue();
					break;
				case 'page':
					if (TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('HTTP_USER_AGENT') != 'nawork_uri') {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $configReader->getPageNotFoundConfigBehaviorValue());
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
						curl_setopt($curl, CURLOPT_TIMEOUT, 30);
						curl_setopt($curl, CURLOPT_USERAGENT, 'nawork_uri');
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
						curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
						curl_setopt($curl, CURLOPT_REFERER, TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
						$output = $this->curl_exec_follow($curl);
					} else {
						$output = '404 not found! The 404 Page URL ' . $configReader->getPageNotFoundConfigBehaviorValue() . ' seems to cause a loop.';
					}
					break;
				case 'redirect':
					$path = html_entity_decode($configReader->getPageNotFoundConfigBehaviorValue());
					if (!($_SERVER['REQUEST_METHOD'] == 'POST' && preg_match('/index.php/', $_SERVER['SCRIPT_NAME']))) {
						tx_naworkuri_helper::sendRedirect($path, 301); // send headers and exits
					}
				default:
					$output = '';
			}
		} else {
			$output = '<html><head><title>404 Not found</title></head><body><h1>Not found!</h1><p>The page you are trying to access is not available</p></body></html>';
		}
		echo $output;
		exit(0);
	}

}

?>
