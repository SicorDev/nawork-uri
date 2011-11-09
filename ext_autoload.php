<?php

$extPath = t3lib_extMgm::extPath('nawork_uri');
$libsPath = t3lib_extMgm::extPath('nawork_uri') . 'lib/';

return array(
	'tx_naworkuri_configreader' => $libsPath . 'class.tx_naworkuri_configReader.php',
	'tx_naworkuri_basic_tc' => $libsPath . '../tests/class.tx_naworkuri_basic_tc.php',
	'tx_naworkuri_transformer' => $libsPath . 'class.tx_naworkuri_transformer.php',
	'tx_naworkuri_exception_urlisredirectexception' => $extPath . 'Classes/Exception/UrlIsRedirectException.php',
	'tx_naworkuri_exception_transformationvaluenotfoundexception' => $extPath . 'Classes/Exception/TransformationValueNotFoundException.php',
	'tx_naworkuri_path' => $extPath . 'Classes/Validation/class.tx_naworkuri_path.php',
	'tx_naworkuri_cache_transformationcache' => $extPath . 'Classes/Cache/TransformationCache.php',
);
?>
