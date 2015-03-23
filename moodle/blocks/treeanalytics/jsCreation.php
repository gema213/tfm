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
$studentValues=array();
$studentValues['QUIZZES']=0;
$studentValues['RESOURCES']=0;
$studentValues['RECOMMENDEDRESOURCES']=0;
$studentValues['TIMETOQUIZZES']=0;
$studentValues['TIMETORESOURCES']=0;
$studentValues['TIMETOASSIGNMENTS']=0;
$studentValues['TIMETORECOMMENDED']=0;
$studentValues['TIMETOFIRSTACTION']=0;

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

 function createNode($name,$parent,$hasChildren){
         $node=array();//array que forma el nodo
         $node['name']=$name;
         $node['parent']=$parent;
         $node['value']=10;
         $node['active']=0;
         if($hasChildren){
                 $node['children']=array();
         }
         return $node;
}

function createJSON(){
	$studentValues['QUIZZES']=rand(0,100)/100;
	$studentValues['RESOURCES']=rand(0,100)/100;
	$studentValues['RECOMMENDEDRESOURCES']=rand(0,100)/100;
	$studentValues['TIMETOQUIZZES']=rand(0,100)/100;
	$studentValues['TIMETORESOURCES']=rand(0,100)/100;
	$studentValues['TIMETOASSIGNMENTS']=rand(0,100)/100;
	$studentValues['TIMETORECOMMENDED']=rand(0,100)/100;
	$studentValues['TIMETOFIRSTACTION']=rand(0,100)/100;


	$studentValues['QUIZZES']=assignValue($studentValues['QUIZZES'],'threshold.quizzes.low','threshold.quizzes.high' );
	$studentValues['RESOURCES']=assignValue($studentValues['RESOURCES'],'threshold.resources.low','threshold.resources.high' );
	$studentValues['RECOMMENDEDRESOURCES']=assignValue($studentValues['RECOMMENDEDRESOURCES'],'threshold.recommendedresources.low','threshold.recommendedresources.high' );
	$studentValues['TIMETOQUIZZES']=assignValue($studentValues['TIMETOQUIZZES'],'threshold.timetoquizzes.low','threshold.timetoquizzes.high' );
	$studentValues['TIMETORESOURCES']=assignValue($studentValues['TIMETORESOURCES'],'threshold.timetoresources.low','threshold.timetoresources.high' );
	$studentValues['TIMETOASSIGNMENTS']=assignValue($studentValues['TIMETOASSIGNMENTS'],'threshold.timetoassignments.low','threshold.timetoassignments.high' );
	$studentValues['TIMETORECOMMENDED']=assignValue($studentValues['TIMETORECOMMENDED'],'threshold.timetorecommended.low','threshold.timetorecommended.high' );
	$studentValues['TIMETOFIRSTACTION']=assignValue($studentValues['TIMETOFIRSTACTION'],'threshold.timetofirstaction.low','threshold.timetofirstaction.high' );
	
	$values.='Quizzes: '.$studentValues['QUIZZES'].'<br>Resources: '.$studentValues['RESOURCES'].'<br>Recomm. Resources: '.$studentValues['RECOMMENDEDRESOURCES'].'<br>Time To Quizzes: '.$studentValues['TIMETOQUIZZES'].'<br>Time To Resources: '.$studentValues['TIMETORESOURCES'].'<br>TimeToAssign: '.$studentValues['TIMETOASSIGNMENTS'].'<br>Time To Recomm.: '.$studentValues['TIMETORECOMMENDED'].'<br>Time To 1stAction: '.$studentValues['TIMETOFIRSTACTION'];


	$values.='<br>****************<br><br>';

	$rulesURL='http://'.$_SERVER['SERVER_ADDR'].$_SERVER['REQUEST_URI'].'/blocks/treeanalytics/rules.xml';

	$xmlIterator = new SimpleXMLIterator($rulesURL,0,TRUE);

	$hasRules=false;
	if(iterator_count($xmlIterator)!=0)  
		{$hasRules=true;
	}

	$json=array();
	$userNode= createNode('User',null,$hasRules);
	if($hasRules){
$userNode['children']['testing']='Meh';
//		array_push($userNode['children'],'Meeeh');
	}
array_push($json,$userNode);
//$values.='Count:'.count($userNode['children']).$userNode['children'][0].'<br><br>';

$values.=getDataTexto($xmlIterator);	
//$json=getData($xmlIterator);
$values.='<br><br>******TESTING*******<br>'.getDataTexto($json).'<br>';

	return $values;
}

function getDataTexto($iterator,$actualNode,$isActive=0,$numConditions=0){
	$contCondition=1;
	foreach($iterator as $keyInitial => $valueInitial){
		switch($keyInitial){
		case 'rule':
			$ret.=getDataTexto($valueInitial);
		break;	
/*		case 'name':
		break;
*/		case 'tree':
			$ret.='<<<'.$valueInitial.'>>>';
		break;
		case 'expression':
			$numConditions=iterator_count($valueInitial);
			$ret.=getDataTexto($valueInitial,0,1,$numConditions);
		break;
		case 'condition':
			$ret.='<br>#condition '.$contCondition.' of '.$numConditions.'#';
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
		break;
		case 'performance':
			$ret.='<br><br>### '.$valueInitial.' ###<br>~~~~~~<br>';
		break;
		default:
			$ret.='<br>'.$keyInitial.' --- ';
			if($valueInitial===null){
				$ret.='null';
			}else{
				$ret.=$valueInitial;
			}
$ret.=getDataTexto($valueInitial);
//			$ret.=getDataTexto($valueInitial).' >> '.iterator_count($valueInitial);
		break;
		}
		$contCondition++;
	}
//	$ret.='//////<br>';
	return $ret;
}

