<?php
	function iniFile(){
		return parse_ini_file('thresholds.ini');
	}
	function xmlFile(){
		$rulesURL='http://'.$_SERVER['SERVER_ADDR'].$_SERVER['REQUEST_URI'].'/blocks/treeanalytics/rules.xml';
		return new SimpleXMLIterator($rulesURL,0,TRUE);
	}
