<?php
$ok=0;
$fail=0;
function assertEquals($expected,$real){
	global $ok,$fail;
	if($expected==$real){
		$ok++;
		echo "\n\tOK\n";
		return;
	}else{
		$fail++;
		echo "\n\tFAIL\n";
		return;
	}
}


function startTest(){
	echo "\n\n\nSTART.....\n\n\n";
}
function testingFile($file){
echo "\nTesting..... ".$file;
require_once($file);
}

function endTest(){
	global $ok,$fail;
	echo "\n\n\n";
	echo "RESULTS\n";
	echo "\tOK: ".$ok."\n";
	echo "\tFAIL: ".$fail."\n";
	echo "\n.....END\n\n\n";
}
//********************************************************************************
startTest();

testingFile('filesConnection.php');

assertEquals(16,sizeof(iniFile()));
assertEquals(104,xmlFileTest()->count());

testingFile('htmlCreation.php');

testingFile('jsCreation.php');

endTest();
