<?php
require('filesConnection.php');

$studentValues=array();
$studentValues['QUIZZES']=0;
$studentValues['RESOURCES']=0;
$studentValues['RECOMMENDEDRESOURCES']=0;
$studentValues['TIMETOQUIZZES']=0;
$studentValues['TIMETORESOURCES']=0;
$studentValues['TIMETOASSIGNMENTS']=0;
$studentValues['TIMETORECOMMENDED']=0;
$studentValues['TIMETOFIRSTACTION']=0;


function createJSONGe(){
	generateStudentValues();

	$xmlIterator=xmlFile();

	$hasRules=false;
	if(iterator_count($xmlIterator)!=0){
		$hasRules=true;
	}
	$json=array();
	$userNode= createNode('User',null,$hasRules);
	if($hasRules){
		$userNode['children']=getData($xmlIterator,$userNode,1);
	}
	array_push($json,$userNode);
$values.= '*******ARRAY******'.getDataTexto($json);
$values.= '<br>*******JSON*******'.json_encode($json);

	return $values;
}

function getData($iterator,$parent,$selectedTree=0,$numConditions=0){
	$ret=Array();
	foreach($iterator as $keyInitial => $valueInitial){
		switch($keyInitial){
			case 'rule':
				$actualAttributes=$valueInitial->attributes();
				$actualTree=$actualAttributes['tree'];
				if($selectedTree==$actualTree ){

//					array_push($ret,getData($valueInitial,$parent));
//				}
/*			break;	
			case 'expression':*/
				$numConditions=iterator_count($valueInitial);
				$ret=getRoute($valueInitial,$parent,0,$numConditions);
}
			break;
		}
	}
	return $ret;
}
function getRoute($iterator,$parent,$currentPos,$numConditions){
	$contCondition=1;
	$hasChildren=false;
	$contPosition=0;

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
		}
		$contPosition++;
		$contCondition++;
	}
	return $ret;
}


 function createNodeGe($name,$parent,$hasChildren){
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

function  getDataTexto($iterator,$actualNode,$numConditions=0){
	$contCondition=1;
	foreach($iterator as $keyInitial => $valueInitial){
		switch($keyInitial){
		case 'rule':
			$ret.=getDataTexto($valueInitial);
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
		break;
		}
		$contCondition++;
	}
	return $ret;
}

function getStudentValues(){
	global $studentValues;
	$values.='Quizzes: '.$studentValues['QUIZZES'].'<br>Resources: '.$studentValues['RESOURCES'].'<br>Recomm. Resources: '.$studentValues['RECOMMENDEDRESOURCES'].'<br>Time To Quizzes: '.$studentValues['TIMETOQUIZZES'].'<br>Time To Resources: '.$studentValues['TIMETORESOURCES'].'<br>TimeToAssign: '.$studentValues['TIMETOASSIGNMENTS'].'<br>Time To Recomm.: '.$studentValues['TIMETORECOMMENDED'].'<br>Time To 1stAction: '.$studentValues['TIMETOFIRSTACTION'];
	return $values;
}

function createJS(){
$js='<script>
var margin = {top: 0, right: 0, bottom: 0, left:50},
    width = 1000 - margin.right - margin.left,
    height = 400 - margin.top - margin.bottom;
    
var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg=d3.select(".tree").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	
	// load the external data
';
$js.='var treeData=['.tempJSON().'];';
$js.='  	root = treeData[0];

root.x0=0;
root.y0=height/2;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  update(root);

function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parents previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .on("click", click);

  nodeEnter.append("circle")
    .attr("r", function(d){return d.value;})
	.attr("class", function(d){
						if(d.active==1){
							if(d.name=="GOOD"){return "good";} 
							if( d.name=="PASS"){return "pass";}
							if(d.name=="FAIL"){return "fail";}			
							if(d.name=="UNKNOWN"){return "unknown";}else{
							return "active";}
						}else{
							return "inactive";
						};
					});

  nodeEnter.append("text")
	  .attr("class",function(d){if(d.active==1){return "on"}else{return "off";}})
      .attr("x", function(d) { return d.children || d._children ? -15 : 15; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.name; })

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

  // Update the links
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
function click(d) {
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update(d);
} 
</script>
';

return $js;
}


function tempJSON(){
return'
{
    "name": "User",
    "value": 10,
    "active": 1,
    "children": [
        {
            "parent": "User",
            "value": 10,
            "active": true,
            "isLeaf": 0,
            "name": "RESOURCES=LOW",
            "children": [
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": true,
                    "isLeaf": 0,
                    "name": "QUIZZES=LOW",
                    "children": [
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 1,
                            "isLeaf": 1,
                            "name": "FAIL"
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOQUIZZES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOQUIZZES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 1,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=LOW",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=LOW",
                                    "value": 10,
                                    "active": 1,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOASSIGNMENTS=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOASSIGNMENTS=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOEXTERNALS=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOEXTERNALS=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "QUIZZES=MEDIUM",
                    "children": [
                        {
                            "parent": "QUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOQUIZZES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOQUIZZES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "QUIZZES=HIGH",
                    "children": [
                        {
                            "parent": "QUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        },
                        {
                            "parent": "QUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOASSIGNMENTS=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOASSIGNMENTS=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOQUIZZES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOQUIZZES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOFIRSTACTION=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOFIRSTACTION=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=LOW",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOEXTERNALS=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOEXTERNALS=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOFIRSTACTION=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOFIRSTACTION=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=LOW",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETORESOURCES=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETORESOURCES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "EXTERNALRESOURCES=MEDIUM",
                    "children": [
                        {
                            "parent": "EXTERNALRESOURCES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "PASS"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=LOW",
                    "value": 10,
                    "active": 1,
                    "isLeaf": 0,
                    "name": "TIMETOEXTERNALS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 1,
                            "isLeaf": 1,
                            "name": "PASS"
                        }
                    ]
                }
            ]
        },
        {
            "parent": "User",
            "value": 10,
            "active": false,
            "isLeaf": 0,
            "name": "RESOURCES=MEDIUM",
            "children": [
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "QUIZZES=LOW",
                    "children": [
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOEXTERNALS=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOEXTERNALS=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "PASS"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "QUIZZES=MEDIUM",
                    "children": [
                        {
                            "parent": "QUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 0,
                                    "name": "EXTERNALRESOURCES=MEDIUM",
                                    "children": [
                                        {
                                            "parent": "EXTERNALRESOURCES=MEDIUM",
                                            "value": 10,
                                            "active": 0,
                                            "isLeaf": 1,
                                            "name": "FAIL"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "PASS"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "QUIZZES=HIGH",
                    "children": [
                        {
                            "parent": "QUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOFIRSTACTION=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOFIRSTACTION=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=LOW",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOEXTERNALS=LOW",
                            "children": [
                                {
                                    "parent": "TIMETOEXTERNALS=LOW",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=LOW",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=LOW",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOQUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOFIRSTACTION=LOW",
                            "children": [
                                {
                                    "parent": "TIMETOFIRSTACTION=LOW",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "PASS"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=LOW",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOEXTERNALS=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOEXTERNALS=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOFIRSTACTION=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOEXTERNALS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=MEDIUM",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "EXTERNALRESOURCES=LOW",
                    "children": [
                        {
                            "parent": "EXTERNALRESOURCES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                }
            ]
        },
        {
            "parent": "User",
            "value": 10,
            "active": false,
            "isLeaf": 0,
            "name": "RESOURCES=HIGH",
            "children": [
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOEXTERNALS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOQUIZZES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOQUIZZES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOFIRSTACTION=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETOFIRSTACTION=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOEXTERNALS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOEXTERNALS=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOEXTERNALS=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOEXTERNALS=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOEXTERNALS=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOEXTERNALS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOASSIGNMENTS=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOASSIGNMENTS=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOEXTERNALS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=LOW",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "QUIZZES=HIGH",
                            "children": [
                                {
                                    "parent": "QUIZZES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "GOOD"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "TIMETOASSIGNMENTS=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "EXTERNALRESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "EXTERNALRESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "TIMETOASSIGNMENTS=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": false,
                    "isLeaf": 0,
                    "name": "QUIZZES=LOW",
                    "children": [
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETOQUIZZES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETOQUIZZES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        },
                        {
                            "parent": "QUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "QUIZZES=MEDIUM",
                    "children": [
                        {
                            "parent": "QUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "QUIZZES=HIGH",
                    "children": [
                        {
                            "parent": "QUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=MEDIUM",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=MEDIUM",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "PASS"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=LOW",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOQUIZZES=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOQUIZZES=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 0,
                            "name": "TIMETORESOURCES=HIGH",
                            "children": [
                                {
                                    "parent": "TIMETORESOURCES=HIGH",
                                    "value": 10,
                                    "active": 0,
                                    "isLeaf": 1,
                                    "name": "FAIL"
                                }
                            ]
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=LOW",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=MEDIUM",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=MEDIUM",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETOFIRSTACTION=HIGH",
                    "children": [
                        {
                            "parent": "TIMETOFIRSTACTION=HIGH",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "FAIL"
                        }
                    ]
                },
                {
                    "parent": "RESOURCES=HIGH",
                    "value": 10,
                    "active": 0,
                    "isLeaf": 0,
                    "name": "TIMETORESOURCES=LOW",
                    "children": [
                        {
                            "parent": "TIMETORESOURCES=LOW",
                            "value": 10,
                            "active": 0,
                            "isLeaf": 1,
                            "name": "GOOD"
                        }
                    ]
                }
            ]
        }
    ]
}
';
}
