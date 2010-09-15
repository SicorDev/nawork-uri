<?php

require_once (t3lib_extMgm::extPath('nawork_uri').'/lib/class.tx_naworkuri_helper.php');

class tx_naworkuri_cache {
	
	private $helper;
	/**
	 *
	 * @var tx_naworkuri_configReader
	 */
	private $config;
	private $timeout = false;
	
	/**
	 * Constructor
	 *
	 */
	public function __construct ($config){
		$this->helper = t3lib_div::makeInstance('tx_naworkuri_helper');
		$this->config = $config;
	}
	
	/**
	 * set Timeout for cache
	 * @param int $to
	 * @return unknown_type
	 */
	public function setTimeout ($to){
		$this->timeout = $to;
	} 
	
	/**
	 * Read a previously created URI from cache 
	 *
	 * @param array $params Parameter Array
	 * @param string $domain current Domain
	 * @return string URI if found otherwise false
	 */
	public function read_params ($params, $domain){
		$uid   = (int)$params['id'];
		$lang  = (int)($params['L'])?$params['L']:0;
		
		unset($params['id']);
		unset($params['L']);
		 
		$imploded_params =$this->helper->implode_parameters($params);
		
		$row = $this->read($uid, $lang, $domain, $imploded_params);
		if ($row){
			return $row['path'];
		} else {
			return false;
		}
		
	}
	
	/**
	 * Read Cache entry for the given URI
	 *
	 * @param string $path URI Path
	 * @param string $domain Current Domain
	 * @return array cache result
	 */
	public function read_path ($path, $domain){
		$hash_path = md5($path);
  		$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
  			'pid, sys_language_uid, params',
			$this->config->getUriTable(),
  			'deleted=0 AND hidden=0 AND hash_path="'.$hash_path.'" AND domain="'.$domain.'"' 
  		);
  		
        if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres) ){
        	return $row;
        }
		return false;
	}

	
	/**
	 * Find the cached URI for the given parameters 
	 * 
	 * @param int $id        : the if param
	 * @param int $lang      : the L param
	 * @param string $domain : the current domain '' for not multidomain setups
	 * @param array $params  : other  url parameters
	 * @return string        : uri wich matches to these params otherwise false 
	 */
	public function read ($id, $lang, $domain, $parameters, $ignoreTimeout = false ){
		
		$timeout_condition = '';
		if ($this->timeout>0 && $ignoreTimeout == false){
			$timeout_condition = 'AND ( tstamp > "'.(time()-$this->timeout).'" OR sticky="1" )'; 		
		} 
		
				// lookup in db
		$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, path, sticky', 
			$this->config->getUriTable(),
			'deleted=0 AND hidden=0 AND pid='.$id.' AND sys_language_uid='.$lang.' AND domain="'.$domain.'" AND hash_params = "'.md5($parameters).'" '.$timeout_condition 
		);
		
		if ( $row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres) ){
			return $row;
		} else {
			return false; 
		}
	}
	

	/**
	 * Write a new URI to cache
	 *
	 * @param array $params Parameter Array
	 * @param string $domain current Domain
	 * @param string $path preferred Path
	 * @param string $debug_info Debug Infos
	 * @return string URI wich was stored for the params
	 */
	public function write_params ($params, $domain, $path, $debug_info=''){
		$uid   = (int)$params['id'];
		$lang  = (int)($params['L'])?$params['L']:0;
		
		unset($params['id']);
		unset($params['L']);
		 
		$imploded_params = $this->helper->implode_parameters($params);
		
		return $this->write($uid, $lang, $domain, $imploded_params, $path, $debug_info);
	}
	
	/**
	 * Write a new URI-Parameter combination to the cache
	 *
	 * @param int $id id Parameter
	 * @param int $lang L Parameter
	 * @param string $domain current Domain
	 * @param string $parameters URI Paramters 
	 * @param string $path Preferred URI Path
	 * @param string $debug_info Debig Informations
	 * @return string URI wich was stored for the params
	 */
	public function write($id, $lang, $domain, $parameters, $path, $debug_info = ''){
		
			// check for a uri record to update 
		$cache = $this->read ($id, $lang, $domain, $parameters, true);
		if (is_array($cache)){
			
				// protect sticky uris
			if ( $cache['sticky'] || $cache['path'] == $path) return $cache['path'];
			
				// update other uris
			$path = $this->unique($path, $domain, $cache['uid']);	

			$save_record = array(
				'path'   => $path,
				'hash_path'   => md5($path),
				'tstamp' => time(),
			);
			
			$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
			$GLOBALS['TYPO3_DB']->debugOutput = 1;
			
			$dbres = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->config->getUriTable(), 'uid='.(int)$cache['uid'] , $save_record );
			return ($path);
			
			
		} else {
		
			
				// make uri unique
			$path = $this->unique($path, $domain);
			
				// save in dm
			$save_record = array(
				'pid' => $id,
				'sys_language_uid' =>  $lang,
				'domain' => $domain,
				'path'   => $path,
				'params' => $parameters,
				'hash_path'   => md5($path),
				'hash_params' => md5($parameters),
				'debug_info' => $debug_info,
				'crdate' => time(),
				'tstamp' => time(),
			);
			
			$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
			$GLOBALS['TYPO3_DB']->debugOutput = 1;
			
			$dbres = $GLOBALS['TYPO3_DB']->exec_INSERTquery($this->config->getUriTable(), $save_record );
			return ($path);
		}
	}

		/**
		 * Make shure this URI is unique for the current domain
		 *
		 * @param string $uri URI
		 * @return string unique URI 
		 */
	public function unique($uri, $domain, $uid=0){
		$uriAppend = $this->config->getAppend();
		$baseUri = '';
		/*
		 * if the append is at the end of the uri then remove it for adding the unique part
		 */
		if(!empty($uriAppend) && substr($uri, -strlen($uriAppend)) == $uriAppend) {
			$baseUri = substr($uri, 0, strlen($uri) - strlen($uriAppend));
		}
		$tmp_uri       = $uri;
		$search_hash   = md5($tmp_uri);
		$search_domain = $domain;
		$additionalWhere = '';
		if(!empty($domain)) {
			$additionalWhere .= ' AND domain LIKE \''.$domain.'\'';
		}	
		if($uid > 0) {
			$additionalWhere .= ' AND uid!='.intval($uid);
		}
			
//		if ($exclude_uid) {
//			$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_naworkuri_uri', 'deleted=0 AND hidden=0 AND uid !='.(int)$exclude_uid.' AND domain="'.$search_domain.'" AND hash_path = "'.$search_hash.'"' );
//		} else {
		$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
			$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('uid', $this->config->getUriTable(), 'deleted=0 AND hidden=0 AND hash_path = "'.$search_hash.'"'.$additionalWhere );
//		}
		

		if($dbres > 0){
				// make the uri unique
			$append = 0;
			do {
				$append ++;
				if(!empty($baseUri)) {
					$tmp_uri = $baseUri.'-'.$append.$uriAppend ; // add the unique part and the uri append part to the base uri
				} else {
					$tmp_uri = $append.$uriAppend;
				}
				$search_hash  = md5($tmp_uri);
				$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('uid', $this->config->getUriTable(), 'deleted=0 AND hidden=0 AND hash_path = "'.$search_hash.'"'.$additionalWhere );
			} while ($dbres > 0);
		}
		return $tmp_uri;
	}
	
}
?>