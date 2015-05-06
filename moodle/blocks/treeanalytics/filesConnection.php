<?php
	function iniFile(){
		return parse_ini_file('thresholds.ini');
	}
	function xmlFile(){
		global $CFG;
		$rulesURL=$CFG->wwwroot.'/blocks/treeanalytics/rules.xml';
		return new SimpleXMLIterator($rulesURL,0,TRUE);
	}
