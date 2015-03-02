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
function assignValue($original, $lowLimit,$highLimit){
	$iniFile=iniFile();
	$high='high';
	$medium='medium';
	$low='low';		
	if($original<=$iniFile[$lowLimit]){
		return $low;
	}else{
		if($original>=$iniFile[$highLimit]){
			return $high;
		}else{
			return $medium;
		}
	}
}
 function createNode($name,$parent,$active,$rootnode,$hasChildren){
         $node=array();
         $node['name']=$name;
         $node['parent']=$parent;
         $node['value']=10;
         $node['active']=$active;
         $node['rootnode']=$rootnode;
         if($hasChildren){
                 $node['children']=array();
         }
         return $node;
 }

function getData($iterator,$isActive=0,$numConditions=0){
	$contCondition=1;
	foreach($iterator as $keyInitial => $valueInitial){
		switch($keyInitial){
//		case 'rule':
//			$ret.='<br>####rule###';
//			$ret.=getData($valueInitial);
//		break;	
		case 'name':
//			$ret.='<br>####name###';
		break;
		case 'conditions';
//			$ret.='<br>####conditions###';
			$numConditions=iterator_count($valueInitial);
			$ret.=getData($valueInitial,1,$numConditions);
		break;
		case 'condition':
			$ret.='<br>####condition '.$contCondition.' of '.$numConditions.'###';
			$actualVariable;
			$actualValue;
			foreach($valueInitial as $keyFinal => $valueFinal){

				switch($keyFinal){
					case 'variable':
						$actualVariable=$valueFinal;
					break;
					case 'value':
						$actualValue=$valueFinal;
					break;
				}
			}
			$ret.='<br>'.$actualVariable.': '.$actualValue;
//			$ret.=getData($valueInitial);
		break;
		case 'performance':
			$ret.='<br>###FINAL **** '.$valueInitial.'###<br>~~~~~~';
		break;
		default:
//			$ret.='<br>'.$keyInitial.': '.$valueInitial;
			$ret.=getData($valueInitial);
		break;	
		}
		$contCondition++;
//$ret.='<br>'.$keyInitial.' -- '.$valueInitial;
	}
	return $ret;
}

function createJSON(){
	$valorQuizzes= rand(0,100)/100;
	$valorResources= rand(0,100)/100;
	$valorRecommendedResources= rand(0,100)/100;
	$valorTimeToQuizzes= rand(0,100)/100;
	$valorTimeToResources= rand(0,100)/100;
	$valorTimeToAssignments= rand(0,100)/100;
	$valorTimeToRecommended= rand(0,100)/100;
	$valorTimeToFirstAction= rand(0,100)/100;
	$valorQuizzes=assignValue($valorQuizzes,'threshold.quizzes.low','threshold.quizzes.high' );
	$valorResources=assignValue($valorResources,'threshold.resources.low','threshold.resources.high' );
	$valorRecommendedResources=assignValue($valorRecommendedResources,'threshold.recommendedresources.low','threshold.recommendedresources.high' );
	$valorTimeToQuizzes=assignValue($valorTimeToQuizzes,'threshold.timetoquizzes.low','threshold.timetoquizzes.high' );
	$valorTimeToResources=assignValue($valorTimeToResources,'threshold.timetoresources.low','threshold.timetoresources.high' );
	$valorTimeToAssignments=assignValue($valorTimeToAssignments,'threshold.timetoassignments.low','threshold.timetoassignments.high' );
	$valorTimeToRecommended=assignValue($valorTimeToRecommended,'threshold.timetorecommended.low','threshold.timetorecommended.high' );
	$valorTimeToFirstAction=assignValue($valorTimeToFirstAction,'threshold.timetofirstaction.low','threshold.timetofirstaction.high' );
	
	$values.='Quizzes: '.$valorQuizzes.'<br>Resources: '.$valorResources.'<br>Recomm. Resources: '.$valorRecommendedResources.'<br>Time To Quizzes: '.$valorTimeToQuizzes.'<br>Time To Resources: '.$valorTimeToResources.'<br>TimeToAssign: '.$valorTimeToAssignments.'<br>Time To Recomm.: '.$valorTimeToRecommended.'<br>Time To 1stAction: '.$valorTimeToFirstAction;


	$values.='<br>****************';

	$rulesURL='http://'.$_SERVER['SERVER_ADDR'].$_SERVER['REQUEST_URI'].'/blocks/treeanalytics/rules.xml';

	$xmlIterator = new SimpleXMLIterator($rulesURL,0,TRUE);

	$json=array();
	array_push($json, createNode('User',null,1,1,$xmlIterator->hasChildren()));

//$json= createNode('User',null,1,1,$xmlIterator->hasChildren());

$values.=getData($xmlIterator);	

$json=getData($xmlIterator);
	return $values;
}


