<?php

########################################################################
# Extension Manager/Repository config file for ext "nawork_uri".
#
# Auto generated 21-06-2011 17:23
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'n@work URI',
	'description' => 'Almost the same as RealURL and CoolURI, but with some improvements from n@work. The backwards compatibility to realurl is dropped for better TYPO3 integration. Requirements: PHP 5+ with SimpleXML and cURL enabled! MySQL 4.1+. The solution is based on CoolURI.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '2.0.3',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Martin Ficzel, Thorben Kapp',
	'author_email' => 'ficzel@work.de, kapp@work.de',
	'author_company' => 'n@work',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:248:{s:9:"ChangeLog";s:4:"a7eb";s:10:"README.txt";s:4:"091f";s:8:"TODO.txt";s:4:"d41d";s:22:"class.tx_naworkuri.php";s:4:"4b35";s:16:"ext_autoload.php";s:4:"96a8";s:21:"ext_conf_template.txt";s:4:"d49d";s:12:"ext_icon.gif";s:4:"2836";s:17:"ext_localconf.php";s:4:"aba2";s:14:"ext_tables.php";s:4:"88e5";s:14:"ext_tables.sql";s:4:"5ab4";s:22:"icon_naworkuri_uri.gif";s:4:"396d";s:25:"icon_naworkuri_uri__h.gif";s:4:"edff";s:29:"icon_naworkuri_uri_sticky.gif";s:4:"7f9d";s:32:"icon_naworkuri_uri_sticky__h.gif";s:4:"2234";s:16:"locallang_db.xml";s:4:"3a5a";s:7:"tca.php";s:4:"a789";s:44:"classes/ajax/class.tx_naworkuri_PageInfo.php";s:4:"b29c";s:14:"doc/manual.sxw";s:4:"d26b";s:19:"doc/wizard_form.dat";s:4:"6313";s:20:"doc/wizard_form.html";s:4:"79f3";s:32:"lib/class.tx_naworkuri_cache.php";s:4:"03df";s:39:"lib/class.tx_naworkuri_configReader.php";s:4:"5e2f";s:33:"lib/class.tx_naworkuri_helper.php";s:4:"0b40";s:38:"lib/class.tx_naworkuri_transformer.php";s:4:"a81d";s:40:"lib/class.tx_naworkuri_utf8_to_ascii.php";s:4:"47f0";s:23:"lib/default_UriConf.xml";s:4:"a951";s:21:"lib/transtab/Makefile";s:4:"1519";s:19:"lib/transtab/README";s:4:"4c31";s:23:"lib/transtab/REFERENCES";s:4:"0f62";s:22:"lib/transtab/transcomp";s:4:"7c6b";s:21:"lib/transtab/transtab";s:4:"d2fc";s:29:"lib/transtab/transtab.changes";s:4:"3831";s:35:"lib/transtab/transtab.missing-MES-2";s:4:"9b9e";s:32:"lib/transtab/transtab.repertoire";s:4:"d7fe";s:25:"lib/transtab/transtab.utf";s:4:"a14e";s:27:"lib/utf8_to_ascii/ChangeLog";s:4:"756b";s:25:"lib/utf8_to_ascii/LICENSE";s:4:"1232";s:24:"lib/utf8_to_ascii/README";s:4:"89dc";s:35:"lib/utf8_to_ascii/utf8_to_ascii.php";s:4:"c4c0";s:28:"lib/utf8_to_ascii/db/x00.php";s:4:"bdeb";s:28:"lib/utf8_to_ascii/db/x01.php";s:4:"8e48";s:28:"lib/utf8_to_ascii/db/x02.php";s:4:"9195";s:28:"lib/utf8_to_ascii/db/x03.php";s:4:"14dc";s:28:"lib/utf8_to_ascii/db/x04.php";s:4:"b4ca";s:28:"lib/utf8_to_ascii/db/x05.php";s:4:"3797";s:28:"lib/utf8_to_ascii/db/x06.php";s:4:"e4fa";s:28:"lib/utf8_to_ascii/db/x07.php";s:4:"5dc7";s:28:"lib/utf8_to_ascii/db/x09.php";s:4:"6333";s:28:"lib/utf8_to_ascii/db/x0a.php";s:4:"df23";s:28:"lib/utf8_to_ascii/db/x0b.php";s:4:"55bf";s:28:"lib/utf8_to_ascii/db/x0c.php";s:4:"d3b4";s:28:"lib/utf8_to_ascii/db/x0d.php";s:4:"0efd";s:28:"lib/utf8_to_ascii/db/x0e.php";s:4:"00ba";s:28:"lib/utf8_to_ascii/db/x0f.php";s:4:"c5ab";s:28:"lib/utf8_to_ascii/db/x10.php";s:4:"db5d";s:28:"lib/utf8_to_ascii/db/x11.php";s:4:"6dbc";s:28:"lib/utf8_to_ascii/db/x12.php";s:4:"1911";s:28:"lib/utf8_to_ascii/db/x13.php";s:4:"ac37";s:28:"lib/utf8_to_ascii/db/x14.php";s:4:"618d";s:28:"lib/utf8_to_ascii/db/x15.php";s:4:"6019";s:28:"lib/utf8_to_ascii/db/x16.php";s:4:"363b";s:28:"lib/utf8_to_ascii/db/x17.php";s:4:"0075";s:28:"lib/utf8_to_ascii/db/x18.php";s:4:"382d";s:28:"lib/utf8_to_ascii/db/x1e.php";s:4:"e0d2";s:28:"lib/utf8_to_ascii/db/x1f.php";s:4:"13ff";s:28:"lib/utf8_to_ascii/db/x20.php";s:4:"ad43";s:28:"lib/utf8_to_ascii/db/x21.php";s:4:"eb84";s:28:"lib/utf8_to_ascii/db/x22.php";s:4:"51ad";s:28:"lib/utf8_to_ascii/db/x23.php";s:4:"2b15";s:28:"lib/utf8_to_ascii/db/x24.php";s:4:"98d4";s:28:"lib/utf8_to_ascii/db/x25.php";s:4:"ee79";s:28:"lib/utf8_to_ascii/db/x26.php";s:4:"88c0";s:28:"lib/utf8_to_ascii/db/x27.php";s:4:"72bb";s:28:"lib/utf8_to_ascii/db/x28.php";s:4:"dcbe";s:28:"lib/utf8_to_ascii/db/x2e.php";s:4:"a506";s:28:"lib/utf8_to_ascii/db/x2f.php";s:4:"577f";s:28:"lib/utf8_to_ascii/db/x30.php";s:4:"be92";s:28:"lib/utf8_to_ascii/db/x31.php";s:4:"09cb";s:28:"lib/utf8_to_ascii/db/x32.php";s:4:"5781";s:28:"lib/utf8_to_ascii/db/x33.php";s:4:"582f";s:28:"lib/utf8_to_ascii/db/x4d.php";s:4:"8842";s:28:"lib/utf8_to_ascii/db/x4e.php";s:4:"b0d1";s:28:"lib/utf8_to_ascii/db/x4f.php";s:4:"8416";s:28:"lib/utf8_to_ascii/db/x50.php";s:4:"dbcc";s:28:"lib/utf8_to_ascii/db/x51.php";s:4:"fd96";s:28:"lib/utf8_to_ascii/db/x52.php";s:4:"5e3a";s:28:"lib/utf8_to_ascii/db/x53.php";s:4:"2960";s:28:"lib/utf8_to_ascii/db/x54.php";s:4:"3b8d";s:28:"lib/utf8_to_ascii/db/x55.php";s:4:"0681";s:28:"lib/utf8_to_ascii/db/x56.php";s:4:"2b2e";s:28:"lib/utf8_to_ascii/db/x57.php";s:4:"725c";s:28:"lib/utf8_to_ascii/db/x58.php";s:4:"ac31";s:28:"lib/utf8_to_ascii/db/x59.php";s:4:"48c0";s:28:"lib/utf8_to_ascii/db/x5a.php";s:4:"2ae0";s:28:"lib/utf8_to_ascii/db/x5b.php";s:4:"14f1";s:28:"lib/utf8_to_ascii/db/x5c.php";s:4:"50bc";s:28:"lib/utf8_to_ascii/db/x5d.php";s:4:"8622";s:28:"lib/utf8_to_ascii/db/x5e.php";s:4:"fbff";s:28:"lib/utf8_to_ascii/db/x5f.php";s:4:"9a87";s:28:"lib/utf8_to_ascii/db/x60.php";s:4:"e160";s:28:"lib/utf8_to_ascii/db/x61.php";s:4:"eb3b";s:28:"lib/utf8_to_ascii/db/x62.php";s:4:"3e55";s:28:"lib/utf8_to_ascii/db/x63.php";s:4:"ade7";s:28:"lib/utf8_to_ascii/db/x64.php";s:4:"6643";s:28:"lib/utf8_to_ascii/db/x65.php";s:4:"a1bb";s:28:"lib/utf8_to_ascii/db/x66.php";s:4:"2f17";s:28:"lib/utf8_to_ascii/db/x67.php";s:4:"3a4b";s:28:"lib/utf8_to_ascii/db/x68.php";s:4:"1b8b";s:28:"lib/utf8_to_ascii/db/x69.php";s:4:"6def";s:28:"lib/utf8_to_ascii/db/x6a.php";s:4:"9fae";s:28:"lib/utf8_to_ascii/db/x6b.php";s:4:"2454";s:28:"lib/utf8_to_ascii/db/x6c.php";s:4:"28fc";s:28:"lib/utf8_to_ascii/db/x6d.php";s:4:"18f8";s:28:"lib/utf8_to_ascii/db/x6e.php";s:4:"df5a";s:28:"lib/utf8_to_ascii/db/x6f.php";s:4:"866c";s:28:"lib/utf8_to_ascii/db/x70.php";s:4:"daf8";s:28:"lib/utf8_to_ascii/db/x71.php";s:4:"6b13";s:28:"lib/utf8_to_ascii/db/x72.php";s:4:"3312";s:28:"lib/utf8_to_ascii/db/x73.php";s:4:"010e";s:28:"lib/utf8_to_ascii/db/x74.php";s:4:"d9c0";s:28:"lib/utf8_to_ascii/db/x75.php";s:4:"34ee";s:28:"lib/utf8_to_ascii/db/x76.php";s:4:"8e1c";s:28:"lib/utf8_to_ascii/db/x77.php";s:4:"9e29";s:28:"lib/utf8_to_ascii/db/x78.php";s:4:"ef15";s:28:"lib/utf8_to_ascii/db/x79.php";s:4:"4146";s:28:"lib/utf8_to_ascii/db/x7a.php";s:4:"bc61";s:28:"lib/utf8_to_ascii/db/x7b.php";s:4:"d065";s:28:"lib/utf8_to_ascii/db/x7c.php";s:4:"5921";s:28:"lib/utf8_to_ascii/db/x7d.php";s:4:"f007";s:28:"lib/utf8_to_ascii/db/x7e.php";s:4:"63d1";s:28:"lib/utf8_to_ascii/db/x7f.php";s:4:"c688";s:28:"lib/utf8_to_ascii/db/x80.php";s:4:"9070";s:28:"lib/utf8_to_ascii/db/x81.php";s:4:"06f4";s:28:"lib/utf8_to_ascii/db/x82.php";s:4:"6cb4";s:28:"lib/utf8_to_ascii/db/x83.php";s:4:"d9d3";s:28:"lib/utf8_to_ascii/db/x84.php";s:4:"38c7";s:28:"lib/utf8_to_ascii/db/x85.php";s:4:"b112";s:28:"lib/utf8_to_ascii/db/x86.php";s:4:"d7fa";s:28:"lib/utf8_to_ascii/db/x87.php";s:4:"80a3";s:28:"lib/utf8_to_ascii/db/x88.php";s:4:"e054";s:28:"lib/utf8_to_ascii/db/x89.php";s:4:"19d3";s:28:"lib/utf8_to_ascii/db/x8a.php";s:4:"0938";s:28:"lib/utf8_to_ascii/db/x8b.php";s:4:"0558";s:28:"lib/utf8_to_ascii/db/x8c.php";s:4:"b098";s:28:"lib/utf8_to_ascii/db/x8d.php";s:4:"a9cb";s:28:"lib/utf8_to_ascii/db/x8e.php";s:4:"53a2";s:28:"lib/utf8_to_ascii/db/x8f.php";s:4:"4e0c";s:28:"lib/utf8_to_ascii/db/x90.php";s:4:"1112";s:28:"lib/utf8_to_ascii/db/x91.php";s:4:"f9ee";s:28:"lib/utf8_to_ascii/db/x92.php";s:4:"3be0";s:28:"lib/utf8_to_ascii/db/x93.php";s:4:"beac";s:28:"lib/utf8_to_ascii/db/x94.php";s:4:"4555";s:28:"lib/utf8_to_ascii/db/x95.php";s:4:"8ee1";s:28:"lib/utf8_to_ascii/db/x96.php";s:4:"57b3";s:28:"lib/utf8_to_ascii/db/x97.php";s:4:"fcfa";s:28:"lib/utf8_to_ascii/db/x98.php";s:4:"88b0";s:28:"lib/utf8_to_ascii/db/x99.php";s:4:"a092";s:28:"lib/utf8_to_ascii/db/x9a.php";s:4:"d8e1";s:28:"lib/utf8_to_ascii/db/x9b.php";s:4:"ff85";s:28:"lib/utf8_to_ascii/db/x9c.php";s:4:"9866";s:28:"lib/utf8_to_ascii/db/x9d.php";s:4:"64b8";s:28:"lib/utf8_to_ascii/db/x9e.php";s:4:"c9fa";s:28:"lib/utf8_to_ascii/db/x9f.php";s:4:"f1c6";s:28:"lib/utf8_to_ascii/db/xa0.php";s:4:"f27b";s:28:"lib/utf8_to_ascii/db/xa1.php";s:4:"9079";s:28:"lib/utf8_to_ascii/db/xa2.php";s:4:"43ab";s:28:"lib/utf8_to_ascii/db/xa3.php";s:4:"ac0f";s:28:"lib/utf8_to_ascii/db/xa4.php";s:4:"673c";s:28:"lib/utf8_to_ascii/db/xac.php";s:4:"113f";s:28:"lib/utf8_to_ascii/db/xad.php";s:4:"1601";s:28:"lib/utf8_to_ascii/db/xae.php";s:4:"c01c";s:28:"lib/utf8_to_ascii/db/xaf.php";s:4:"98f2";s:28:"lib/utf8_to_ascii/db/xb0.php";s:4:"7f37";s:28:"lib/utf8_to_ascii/db/xb1.php";s:4:"f710";s:28:"lib/utf8_to_ascii/db/xb2.php";s:4:"824a";s:28:"lib/utf8_to_ascii/db/xb3.php";s:4:"fabb";s:28:"lib/utf8_to_ascii/db/xb4.php";s:4:"eff9";s:28:"lib/utf8_to_ascii/db/xb5.php";s:4:"cbf6";s:28:"lib/utf8_to_ascii/db/xb6.php";s:4:"b0d1";s:28:"lib/utf8_to_ascii/db/xb7.php";s:4:"a685";s:28:"lib/utf8_to_ascii/db/xb8.php";s:4:"fd7a";s:28:"lib/utf8_to_ascii/db/xb9.php";s:4:"abb9";s:28:"lib/utf8_to_ascii/db/xba.php";s:4:"36d7";s:28:"lib/utf8_to_ascii/db/xbb.php";s:4:"d547";s:28:"lib/utf8_to_ascii/db/xbc.php";s:4:"078a";s:28:"lib/utf8_to_ascii/db/xbd.php";s:4:"bd9b";s:28:"lib/utf8_to_ascii/db/xbe.php";s:4:"624c";s:28:"lib/utf8_to_ascii/db/xbf.php";s:4:"5121";s:28:"lib/utf8_to_ascii/db/xc0.php";s:4:"1a80";s:28:"lib/utf8_to_ascii/db/xc1.php";s:4:"17fa";s:28:"lib/utf8_to_ascii/db/xc2.php";s:4:"9518";s:28:"lib/utf8_to_ascii/db/xc3.php";s:4:"a472";s:28:"lib/utf8_to_ascii/db/xc4.php";s:4:"9afb";s:28:"lib/utf8_to_ascii/db/xc5.php";s:4:"141d";s:28:"lib/utf8_to_ascii/db/xc6.php";s:4:"a938";s:28:"lib/utf8_to_ascii/db/xc7.php";s:4:"6e9e";s:28:"lib/utf8_to_ascii/db/xc8.php";s:4:"e6a8";s:28:"lib/utf8_to_ascii/db/xc9.php";s:4:"bf4e";s:28:"lib/utf8_to_ascii/db/xca.php";s:4:"897d";s:28:"lib/utf8_to_ascii/db/xcb.php";s:4:"0df0";s:28:"lib/utf8_to_ascii/db/xcc.php";s:4:"910f";s:28:"lib/utf8_to_ascii/db/xcd.php";s:4:"517d";s:28:"lib/utf8_to_ascii/db/xce.php";s:4:"e503";s:28:"lib/utf8_to_ascii/db/xcf.php";s:4:"b346";s:28:"lib/utf8_to_ascii/db/xd0.php";s:4:"0358";s:28:"lib/utf8_to_ascii/db/xd1.php";s:4:"a817";s:28:"lib/utf8_to_ascii/db/xd2.php";s:4:"4102";s:28:"lib/utf8_to_ascii/db/xd3.php";s:4:"58fb";s:28:"lib/utf8_to_ascii/db/xd4.php";s:4:"cd0e";s:28:"lib/utf8_to_ascii/db/xd5.php";s:4:"5d22";s:28:"lib/utf8_to_ascii/db/xd6.php";s:4:"673f";s:28:"lib/utf8_to_ascii/db/xd7.php";s:4:"e23e";s:28:"lib/utf8_to_ascii/db/xf9.php";s:4:"5ce2";s:28:"lib/utf8_to_ascii/db/xfa.php";s:4:"0bec";s:28:"lib/utf8_to_ascii/db/xfb.php";s:4:"add3";s:28:"lib/utf8_to_ascii/db/xfc.php";s:4:"04c1";s:28:"lib/utf8_to_ascii/db/xfd.php";s:4:"6e99";s:28:"lib/utf8_to_ascii/db/xfe.php";s:4:"6172";s:28:"lib/utf8_to_ascii/db/xff.php";s:4:"6731";s:33:"lib/utf8_to_ascii/tests/index.php";s:4:"b65b";s:37:"lib/utf8_to_ascii/tests/data/utf8.txt";s:4:"4f55";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"774a";s:14:"mod1/index.php";s:4:"869d";s:18:"mod1/locallang.xml";s:4:"d5b5";s:16:"mod1/logfile.gif";s:4:"415b";s:22:"mod1/mod_template.html";s:4:"145c";s:19:"mod1/moduleicon.gif";s:4:"2836";s:48:"modfunc_info/class.tx_naworkuri_modfunc_info.php";s:4:"8bd0";s:26:"modfunc_info/locallang.xml";s:4:"453d";s:30:"resources/css/naworkuri_be.css";s:4:"0a9a";s:24:"resources/images/add.png";s:4:"1988";s:25:"resources/images/edit.png";s:4:"a34e";s:45:"resources/javascript/Ext.grid.ButtonColumn.js";s:4:"dc7a";s:44:"resources/javascript/Ext.grid.CheckColumn.js";s:4:"9c23";s:49:"resources/javascript/Ext.grid.ObservableColumn.js";s:4:"d1bd";s:46:"resources/javascript/Ext.ux.grid.RowActions.js";s:4:"dd62";s:36:"resources/javascript/tx.naworkuri.js";s:4:"4e5e";s:45:"resources/javascript/tx.naworkuri.pageinfo.js";s:4:"d1a4";s:45:"resources/javascript/tx.naworkuri.pageuris.js";s:4:"a934";s:46:"resources/javascript/tx.naworkuri.urisearch.js";s:4:"e140";s:37:"tests/class.tx_naworkuri_basic_tc.php";s:4:"37c6";s:43:"tests/class.tx_naworkuri_cache_testcase.php";s:4:"3967";s:44:"tests/class.tx_naworkuri_configreader_tc.php";s:4:"293b";s:44:"tests/class.tx_naworkuri_helper_testcase.php";s:4:"304f";s:49:"tests/class.tx_naworkuri_transformer_testcase.php";s:4:"fc4d";s:51:"tests/class.tx_naworkuri_utf8_to_ascii_testcase.php";s:4:"7799";s:22:"tests/test_UriConf.xml";s:4:"a351";}',
	'suggests' => array(
	),
);

?>
