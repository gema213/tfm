<?php
	function iniFile(){
		return parse_ini_file('thresholds.ini');
	}
	function xmlFile(){
/*
$data = implode("", file("rules.xml"));
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

return $tags;*/
		return simplexml_load_file('rules.xml');

	}
