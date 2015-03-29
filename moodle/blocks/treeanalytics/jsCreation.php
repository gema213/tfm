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

function generateStudentValues(){
global $studentValues;
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
};
function getStudentValues(){
global $studentValues;
	$values.='Quizzes: '.$studentValues['QUIZZES'].'<br>Resources: '.$studentValues['RESOURCES'].'<br>Recomm. Resources: '.$studentValues['RECOMMENDEDRESOURCES'].'<br>Time To Quizzes: '.$studentValues['TIMETOQUIZZES'].'<br>Time To Resources: '.$studentValues['TIMETORESOURCES'].'<br>TimeToAssign: '.$studentValues['TIMETOASSIGNMENTS'].'<br>Time To Recomm.: '.$studentValues['TIMETORECOMMENDED'].'<br>Time To 1stAction: '.$studentValues['TIMETOFIRSTACTION'];
return $values;
}

function createJSON(){
	generateStudentValues();
//	$values.=getStudentValues();
	$rulesURL='http://'.$_SERVER['SERVER_ADDR'].$_SERVER['REQUEST_URI'].'/blocks/treeanalytics/rules.xml';

	$xmlIterator = new SimpleXMLIterator($rulesURL,0,TRUE);

	$hasRules=false;
	if(iterator_count($xmlIterator)!=0){
		$hasRules=true;
	}
	$json=array();
	$userNode= createNode('User',null,$hasRules);
	if($hasRules){
		$userNode['children']=getData($xmlIterator,$userNode,null);
	}
	array_push($json,$userNode);

$values.= '*******JSON*******'.json_encode($json);

	return $values;
}


function getData($iterator,$parent,$numConditions=0){
	$ret=Array();
	foreach($iterator as $keyInitial => $valueInitial){
		switch($keyInitial){
			case 'rule':
//$ret.=getData($valueInitial,$parent);

				array_push($ret,getData($valueInitial,$parent));

			break;	
			case 'name':
			break;
			case 'tree':
//				$ret.='<<<'.$valueInitial.'>>>';
			break;
			case 'expression':
				$numConditions=iterator_count($valueInitial);
				//array_push($ret,getRoute($valueInitial,$parent,0,$numConditions));
//$ret.=count($valueInitial);
$ret=getRoute($valueInitial,$parent,0,$numConditions);
			break;
		}
	}
	return $ret;
}
function getRoute($iterator,$parent,$currentPos,$numConditions){
	$contCondition=1;
	$hasChildren=false;
$contPosition=0;
//	$slice =array_slice($iterator,0,1,true); //WRONG
	$ret=Array();
	foreach($iterator as $keyInitial => $valueInitial) {	
if($contPosition==$currentPos){
		switch($keyInitial){
			case 'condition':
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
				if($contCondition<$numConditions){$hasChildren=true;}
				$actualNode=createNode($actualVariable.'='.$actualValue,$parent['name'],$hasChildren);
				if($hasChildren){
					$parent=$actualNode;
					array_push($actualNode['children'],getRoute($iterator,$parent,$contCondition,$numConditions));
				}
				$ret=$actualNode;
			break;
			case 'performance':
				$hasChildren=false;
				$actualNode=createNode((string)$valueInitial,$parent['name'],$hasChildren);
				$ret=$actualNode;
			break;		
		}

}$contPosition++;
		$contCondition++;
	}
	return $ret;
}



function  getDataTexto($iterator,$actualNode,$numConditions=0){
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
			$ret.=getDataTexto($valueInitial,0,$numConditions);
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
			$ret.='<br>['.$keyInitial.' --- ';
			if($valueInitial===null){
				$ret.='null';
			}else{
				$ret.=$valueInitial;
			}
$ret.=getDataTexto($valueInitial).']';
//			$ret.=getDataTexto($valueInitial).' >> '.iterator_count($valueInitial);
		break;
		}
		$contCondition++;
	}
//	$ret.='//////<br>';
	return $ret;
}

function createJS(){
$js='<script>var margin = {top: 0, right: 0, bottom: 0, left:50},
    width = 1000 - margin.right - margin.left,
    height = 400 - margin.top - margin.bottom;
    
var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

//var svg = d3.select(".popupBoxContent").append("svg")
var svg=d3.select(".tree").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");';
//$js.='var treeData='.createJSON().';';
$js.='var url ="http://156.35.95.149/pruebas/generated.json";
d3.json(url, function (treeData) {';
$js.='
  	root = treeData[0];

	  //---->>>
  root.x0 = height / 2;
  root.y0 = 0;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  //----<<<
  update(root);';
$js.='});';
$js.='/*

d3.select(self.frameElement).style("height", "800px");
*/
function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes…
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parents previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .on("click", click);//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<-----------------------------------

  nodeEnter.append("circle")
    .attr("r", function(d){return d.value;})
	.attr("class", function(d){
						if(d.active==1){
							if(d.name=="GOOD" || d.name=="PASS"){return "pass";}else{
							if(d.name=="FAIL"){return "fail";}else{
							if(d.name=="UNKNOWN"){return "unknown";}else{
							return "active";}}}
						}else{
							return "inactive";
						};
					});
      /*.style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });*/

  nodeEnter.append("text")
	  .attr("class",function(d){if(d.active==1){return "on"}else{return "off";}})
      .attr("x", function(d) { return d.children || d._children ? -15 : 15; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.name; })
      /*.style("fill-opacity", 1e-6)*/;

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
     .attr("r", function(d){return d.value;});

  // Transition exiting nodes to the parents new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-10);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links…
  var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links at the parents previous position.
  link.enter().insert("path", "g")
				   .attr("class", function(d) {
						if (d.target.active == 1) {
							return "link active";
						}
						return "link inactive";
					})
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition exiting nodes to the parents new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}

// Toggle children on click.
//----------->>>>>>>>>>>
function click(d) {
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update(d);
} //------<<<<<<<<<<<<<
</script>';
return $js;
//return '<script src="http://156.35.95.149/moodle/blocks/treeanalytics/tree.js"/>';
}
