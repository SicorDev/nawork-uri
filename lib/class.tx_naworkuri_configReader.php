<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Thorben Kapp <thorben@work.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Description of class
 *
 * @author Thorben Kapp <thorben@work.de>
 */
class tx_naworkuri_configReader implements t3lib_Singleton {
	
	/**
	 *
	 * @var SimpleXMLElement
	 */
	protected $config;

	public function  __construct($configFile = '') {
		global $TYPO3_CONF_VARS;
		$this->config = new SimpleXMLElement(PATH_site.$configFile, null, true);
		$this->validateConfig();
	}
	
	public function getCastTypeToInt() {
		return (boolean)(int)$this->config->castTypeToInt;
	}
	
	public function getCastLToInt() {
		return (boolean)(int)$this->config->castLToInt;
	}
	
	public function getRedirectOnParameterDiff() {
		return (boolean)(int)$this->config->redirectOnParameterDiff;
	}
	
	public function getRedirectStatus() {
		return (int)$this->config->redirectStatus;
	}

	public function getPagePathTableName() {
		return (string)$this->config->pagepath->table;
	}

	public function getPagePathField() {
		return (string)$this->config->pagepath->field;
	}

	public function getPagePathLimit() {
		return (int)$this->config->pagepath->limit;
	}

	public function hasPagePathConfig() {
		return is_a($this->config->pagepath, 'SimpleXMLElement') ? true : false;
	}

	public function getPageNotFoundConfigStatus() {
		return (string)$this->config->pagenotfound->status;
	}

	public function getPageNotFoundConfigBehaviorType() {
		return (string)$this->config->pagenotfound->behavior->attributes()->type;
	}

	public function getPageNotFoundConfigBehaviorValue() {
		return (string)$this->config->pagenotfound->behavior;
	}

	public function hasPageNotFoundConfig() {
		if(is_a($this->config->pagenotfound, 'SimpleXMLElement')) {
			return true;
		}
		return false;
	}

	public function getDomainTable() {
		return (string)$this->config->domaintable;
	}

	public function getUriTable() {
		return (string)$this->config->uritable;
	}

	public function getParamOrder() {
		return $this->config->paramorder->children();
	}

	public function getAppend() {
		return is_a($this->config->append, 'SimpleXMLElement') ? (string)$this->config->append : '';
	}

	public function getPredefinedParts() {
		return $this->config->predefinedparts->children();
	}

	public function getValueMaps() {
		return $this->config->valuemaps->children();
	}

	public function getUriParts() {
		return $this->config->uriparts->children();
	}

	private function validateConfig() {
		if(!is_a($this->config->uritable, 'SimpleXMLElement')) {
			$this->config->addChild('uritable', 'tx_naworkuri_uri');
		} else {
			$uriTable = (string)$this->config->uritable;
			if(empty($uriTable)) {
				$this->config->uritable = 'tx_naworkuri_uri';
			}
		}

		if(!is_a($this->config->domaintable, 'SimpleXMLElement')) {
			$this->config->addChild('domaintable', 'sys_domain');
		} else {
			$domaintable = (string)$this->config->domaintable;
			if(empty($domaintable)) {
				$this->config->domaintable = 'sys_domain';
			}
		}

		if(!is_a($this->config->pagepath->table, 'SimpleXMLElement')) {
			$this->config->addChild('pagepath->table', 'pages');
		} else {
			$pagepath->table = (string)$this->config->pagepath->table;
			if(empty($pagepath->table)) {
				$this->config->pagepath->table = 'pages';
			}
		}
		
		if(!$this->config->castTypeToInt instanceof SimpleXMLElement) {
			$this->config->addChild('castTypeToInt', 1);
		} else {
			$castTypeToInt = (int)$this->config->castTypeToInt;
			if(empty($castTypeToInt)) {
				$this->config->castTypeToInt = 1;
			}
		}
		
		if(!$this->config->castLToInt instanceof SimpleXMLElement) {
			$this->config->addChild('castLToInt', 1);
		} else {
			$castLToInt = (int)$this->config->castLToInt;
			if(empty($castLToInt)) {
				$this->config->castLToInt = 1;
			}
		}
		
		if(!$this->config->redirectOnParameterDiff instanceof SimpleXMLElement) {
			$this->config->addChild('redirectOnParameterDiff', 1);
		} else {
			$redirectOnParameterDiff = (int)$this->config->redirectOnParameterDiff;
			if(empty($redirectOnParameterDiff)) {
				$this->config->redirectOnParameterDiff = 1;
			}
		}
		
		if(!$this->config->redirectStatus instanceof SimpleXMLElement) {
			$this->config->addChild('redirectStatus', 1);
		} else {
			$redirectStatus = (int)$this->config->redirectStatus;
			if(empty($redirectStatus)) {
				$this->config->redirectStatus = 1;
			}
		}
	}
}
?>
