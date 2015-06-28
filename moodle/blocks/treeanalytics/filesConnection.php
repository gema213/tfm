<?php
	function iniFile(){
		return parse_ini_file('thresholds.ini');
	}
	function xmlFile(){
		global $CFG;
		$rulesURL=$CFG->wwwroot.'/blocks/treeanalytics/rules.xml';
		return new SimpleXMLIterator($rulesURL,0,TRUE);
	}

	function xmlFileTest(){
		return new SimpleXMLIterator('http://156.35.95.149/moodle/blocks/treeanalytics/rules.xml',0,TRUE);
	}

