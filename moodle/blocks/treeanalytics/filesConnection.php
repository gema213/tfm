<?php
	function iniFile(){
		return parse_ini_file('thresholds.ini');
	}
	function xmlFile(){
		return simplexml_load_file('rules.xml');
	}
