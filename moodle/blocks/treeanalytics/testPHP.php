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
assertEquals(103,xmlFileTest()->count());

testingFile('htmlCreation.php');

testingFile('jsCreation.php');

$studentValues=array(
		"QUIZZES"=>700,
		"RESOURCES"=>2,
		"RECOMMENDEDRESOURCES"=>5,
		"TIMETOQUIZZES"=>-1,
		"TIMETORESOURCES"=>-1,
		"TIMETOASSIGNMENTS"=>-1,
		"TIMETORECOMMENDED"=>-1,
		"TIMETOFIRSTACTION"=>-1
	);
$config=array(
"low"=>0,
"high"=>0
);
assertEquals('NA',assignValue($config,$studentValues["TIMETOQUIZZES"],'low','high','threshold.timetoquizzes.low','threshold.timetoquizzes.high'));

assertEquals('LOW',assignValue($config,$studentValues["RESOURCES"],'low','high','threshold.resources.low','threshold.resources.high'));
assertEquals('MEDIUM',assignValue($config,$studentValues["RECOMMENDEDRESOURCES"],'low','high','threshold.recommendedresources.low','threshold.recommendedresources.high'));
assertEquals('HIGH',assignValue($config,$studentValues["QUIZZES"],'low','high','threshold.quizzes.low','threshold.quizzes.high'));

assertEquals('User',createRoot()->name);
assertEquals(10,createRoot()->value);
assertEquals(1,createRoot()->active);
assertEquals(0,sizeof(createRoot()->children));


assertEquals(3,sizeof(json_decode(createJSON(xmlFileTest(),2,$studentValues))->children));
assertEquals('User',json_decode(createJSON(xmlFileTest(),2,$studentValues))->name);

endTest();
