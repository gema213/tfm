<?php
require('filesConnection.php');
function style() {
	return '
		<style type="text/css">
			.node { cursor: pointer;}
			
			.node circle { stroke-width: 3px;}

			.node	.inactive{fill:#ddd;stroke:#ccc;}
			.node	.active{fill:aqua;stroke:blue;}
			.node .good{fill:#00FF00;stroke:#04B404;}/*green*/
			.node .pass{fill:#FE9A2E;stroke:#FF4000;}/*orange*/
			.node .fail{fill:#FF0000;stroke:#610B0B;}/*red*/
			.node .unknown {fill:black;stroke:black;}
	
			.node text {color:green;  font: 12px sans-serif; }
			.node .off {fill:gray;}
			.node text.on{fill:black;}

			.link { fill: none;	stroke: #ccc;stroke-width: 2px;}			
			.link.active{
				stroke:#333;
			}
		</style>
	';
}

function createJSON(){
$high='high';
$medium='medium';
$low='low';
	$valorQuizzes= rand(0,100)/100;
	$valorResources= rand(0,100)/100;
	$valorRecommendedResources= rand(0,100)/100;
	$valorTimeToQuizzes= rand(0,100)/100;
	$valorTimeToResources= rand(0,100)/100;
	$valorTimeToAssignments= rand(0,100)/100;
	$valorTimeToRecommended= rand(0,100)/100;
	$valorTimeToFirstAction= rand(0,100)/100;

	$iniFile=iniFile();

//	$xmlFile=xmlFile();

$values=count($iniFile).'elementos: ';
foreach($iniFile as $actual){
$values.=$actual.' ';
}
$xmlIterator=new SimpleXMLIterator('http://156.35.95.149/moodle/blocks/treeanalytics/rules.xml',0,TRUE);
//$xmlIterator = new SimpleXMLIterator(file_get_contents('rules.xml'),0,false);
for( $xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next() ) {
    foreach($xmlIterator->getChildren() as $name => $data) {
$values.='<br>'.$name.' '.$data;
//    $values.='The $name is '.$data.' from the class ' . get_class($data);
    }
}

return $values;
//	return current($iniFile);

}

