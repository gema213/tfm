<?php
require('filesConnection.php');

/**/
function generateStudentValues(){

	$studentValues=array(
		"QUIZZES"=>rand(0,100)/100,
		"RESOURCES"=>rand(0,100)/100,
		"RECOMMENDEDRESOURCES"=>rand(0,100)/100,
		"TIMETOQUIZZES"=>rand(0,100)/100,
		"TIMETORESOURCES"=>rand(0,100)/100,
		"TIMETOASSIGNMENTS"=>rand(0,100)/100,
		"TIMETORECOMMENDED"=>rand(0,100)/100,
		"TIMETOFIRSTACTION"=>rand(0,100)/100
	);

	$studentValues["QUIZZES"]=assignValue($studentValues["QUIZZES"],'threshold.quizzes.low','threshold.quizzes.high' );
	$studentValues["RESOURCES"]=assignValue($studentValues["RESOURCES"],'threshold.resources.low','threshold.resources.high' );
	$studentValues["RECOMMENDEDRESOURCES"]=assignValue($studentValues["RECOMMENDEDRESOURCES"],'threshold.recommendedresources.low','threshold.recommendedresources.high' );
	$studentValues["TIMETOQUIZZES"]=assignValue($studentValues["TIMETOQUIZZES"],'threshold.timetoquizzes.low','threshold.timetoquizzes.high' );
	$studentValues["TIMETORESOURCES"]=assignValue($studentValues["TIMETORESOURCES"],'threshold.timetoresources.low','threshold.timetoresources.high' );
	$studentValues["TIMETOASSIGNMENTS"]=assignValue($studentValues["TIMETOASSIGNMENTS"],'threshold.timetoassignments.low','threshold.timetoassignments.high' );
	$studentValues["TIMETORECOMMENDED"]=assignValue($studentValues["TIMETORECOMMENDED"],'threshold.timetorecommended.low','threshold.timetorecommended.high' );
	$studentValues["TIMETOFIRSTACTION"]=assignValue($studentValues["TIMETOFIRSTACTION"],'threshold.timetofirstaction.low','threshold.timetofirstaction.high' );

	return $studentValues;
}

/*
TODO: Pensar que como se inicializa a LOW, solo comprobar medium y high.
*/
function assignValue($original, $lowLimit,$highLimit){
	$iniFile=iniFile();
	$high="HIGH";
	$medium="MEDIUM";
	$low="LOW";
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


/*
  Function create to root node.

  @return rootNode
*/
function createRoot(){
	$rootNode = new stdClass();
	$rootNode->name = "User";
	$rootNode->value = 10;
	$rootNode->active = 1;
	$rootNode->children = array();
	return $rootNode;
}

/*
  Function to create a node for the JSON.

  @param $data Actual XML node
  @param $hasChildren Flag to indicate when a node has children
  @param $last_element Name of the parent element
  @return JSON node generated
*/
function createNode($data, $hasChildren, $last_element){
	$node = new stdClass();
	if($last_element == ""){
		$node->parent = "User";
	} else {
		$node->parent = $last_element;
	}
	$node->value = 10;
	$node->active = 0;
	if($hasChildren){
		$node->isLeaf = 0;
		$node->name = $data->variable."=".$data->value;
		$node->children = array();
	} else {
		$node->isLeaf = 1;
		$node->name = $data->__toString();
	}
	return $node;
}



/*
  Function to generate a rule and if it is necessary activate the nodes.

  @param $rule_array Array with all conditions and results of a rule
  @param $is_active Flag to activate nodes
  @return PHP structure to generate JSON
*/
function generateRule($rule_array, $is_active){
	// Auxiliary last node
	$last="";
	// Generate a reverse array to loop from leaf to root node
	$reverse_rule = array_reverse($rule_array);
	// Loop over nodes
	foreach($reverse_rule as $node){
		$node->active=$is_active;
		if($last != "") {
			array_push($node->children, $last);
		}
		// Preserve actual node
		$last=$node;
	}
	// Return root node
	return $last;
}

/*
  Function to append new rule to the tree.

  @param $tree Current tree. By default, root_node
  @param new_rule New rule to append.
  @return tree generated
*/
function appendRule($tree, $new_rule){
	// Check if node has childs
	if(sizeof($tree->children)==0){
		// Push new rule and return
		array_push($tree->children, $new_rule);
		return $tree;
	} else {
		// Auxiliary variable to show when node found
		$has_node = 0;
		// Get children
		$childs = $tree->children;
		// Loop over children
		foreach($childs as $child){
			if($child->isLeaf == 0){
				// Node exists
				if($child->name == $new_rule->name){
					// Change active
					if($child->active == 1 || $new_rule->active == 1){
						$child->active = 1;
					}
					// Change has node
					$has_node = 1;
					$child = appendRule($child, $new_rule->children[0]);
					break;
				}
			}
		}
		// Node not found
		if($has_node == 0){
			array_push($tree->children, $new_rule);
			return $tree;
		} else {
			return $tree;
		}
	}
}

/*
@return JSON
*/
function createJSON($numberTree,$studentValues){

//	$studentValues = generateStudentValues();
	$xmlIterator=xmlFile();

//$numberTree=1;

	//$rules = $xmlIterator -> xpath("//rule[@tree='1']");
	$rules = $xmlIterator -> xpath("//rule[@tree=$numberTree]");

	// Root node
	$rootNode = createRoot();
	
	// Loop over rules
	foreach($rules as $rule){
		// Array for conditions	
		$cond_array = array();
		// Variable to acumulate how many conditions are satisfied
		$sum_conditions = 0;
		// Auxiliary variables: last element
		$last_element = "";
		// Loop over rule conditions
		foreach($rule->condition as $cond){
			$varia = $cond->variable;
			$actual_value = $studentValues["$varia"];
			//$actual_value=$studentValues[$varia];
			$sum_conditions = $sum_conditions + ($actual_value == $cond->value);
			// Add condition to array
			array_push($cond_array, createNode($cond, 1, $last_element));
			// Change auxiliary variables
			$last_element=$cond->variable."=".$cond->value;
		}
		// Generate leaf node
		array_push($cond_array, createNode($rule->performance, 0, $last_element));
		// If sum_conditions = number of conditions then rule satisfied
		if ($sum_conditions == sizeof($rule->condition)){
			// Generate rule and activate nodes
			$rule_tree = generateRule($cond_array, 1);
			$rootNode = appendRule($rootNode, $rule_tree);
		} else {
			// Generate rule with all nodes inactives
			$rule_tree = generateRule($cond_array, 0);
			$rootNode = appendRule($rootNode, $rule_tree);
		}
	}
	// Generate JSON
//	$json = json_encode($rootNode, JSON_PRETTY_PRINT);	
	return json_encode($rootNode, JSON_PRETTY_PRINT);
}

function createJS($numberTree,$studentValues){
 $json=createJSON($numberTree,$studentValues);

$js='<script>
			var margin'.$numberTree.' = {top: 0, right: 0, bottom: 0, left:50},
				width'.$numberTree.' = 1000 - margin'.$numberTree.'.right - margin'.$numberTree.'.left,
				height'.$numberTree.' = 590 - margin'.$numberTree.'.top - margin'.$numberTree.'.bottom;
				
			var i'.$numberTree.' = 0,
				duration'.$numberTree.' = 750,
				root'.$numberTree.';
			var tree'.$numberTree.' = d3.layout.tree()
				.size([height'.$numberTree.', width'.$numberTree.']);
			var diagonal'.$numberTree.' = d3.svg.diagonal()
				.projection(function(d) { return [d.y, d.x]; });
			var svg'.$numberTree.'=d3.select("#tree'.$numberTree.'").append("svg")
				.attr("width", width'.$numberTree.' + margin'.$numberTree.'.right + margin'.$numberTree.'.left)
				.attr("height", height'.$numberTree.' + margin'.$numberTree.'.top + margin'.$numberTree.'.bottom)
			.append("g")
				.attr("transform", "translate(" + margin'.$numberTree.'.left + "," + margin'.$numberTree.'.top + ")");
				
			// Add tooltip div
						var div'.$numberTree.' = d3.select("#tree'.$numberTree.'").append("div")
						.attr("class", "tooltip")
						.style("opacity", 1e-6);
				
				
				
				// load the external data
';
$js.='var treeData'.$numberTree.'=['.$json.'];';
$js.='			
			root'.$numberTree.' = treeData'.$numberTree.'[0];
			root'.$numberTree.'.x0=0;
			root'.$numberTree.'.y0=height'.$numberTree.'/2;

			collapse'.$numberTree.'(root'.$numberTree.');
		
			update'.$numberTree.'(root'.$numberTree.',true);

			function collapse'.$numberTree.'(d) {
				if(d.active==0){
					if (d.children) {
					  d._children = d.children;
					  d._children.forEach(collapse'.$numberTree.');
					  d.children = null;
					}	
				}else{
					if (d.children) {					  
					  d.children.forEach(collapse'.$numberTree.');
					}
				}
			}

			 
			function update'.$numberTree.'(source,initialUpdate) {
			  // Compute the new tree layout.
			  var nodes = tree'.$numberTree.'.nodes(root'.$numberTree.').reverse(),
				  links = tree'.$numberTree.'.links(nodes);
			  // Normalize for fixed-depth.
			  nodes.forEach(function(d) { d.y = d.depth * 180; });
			  // Update the nodes
			  var node = svg'.$numberTree.'.selectAll("g.node")
				  .data(nodes, function(d) { return d.id || (d.id = ++i'.$numberTree.'); });
			  // Enter any new nodes at the parents previous position.
			  var nodeEnter = node.enter().append("g")
				.attr("class", "node")
				.attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
				.on("click", click'.$numberTree.')
				.on("mouseover", function(d){mouseover'.$numberTree.'(d);})
				.on("mousemove", function(d){mousemove'.$numberTree.'(d);})
				.on("mouseout", mouseout'.$numberTree.');
				  
			  var circle=  nodeEnter.append("circle")
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
								
				if(initialUpdate){
					circle.style("display", function(d){
						if(d.active==0){return "none";}
					});
				}	
				
				
			    nodeEnter.append("text")
				  .attr("class",function(d){if(d.active==1){return "on"}else{return "off";}})
				  .attr("x", function(d) { return d.children || d._children ? -15 : 15; })
				  .attr("y", function(d) { return (d.children || d._children)&& d.name!="User" ? 20 : 0; })
				  .attr("dy", ".35em")
				  .attr("text-anchor", function(d) { return d.name=="User" ? "end" : (d.children || d._children ? "middle " : "start");})
				  .text(function(d) { return getNodeName'.$numberTree.'(d.name,true) });
			  // Transition nodes to their new position.
			  var nodeUpdate = node.transition()
				  .duration(duration'.$numberTree.')
				  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });
			  nodeUpdate.select("circle")
				 .attr("r", function(d){return d.value;});
			  // Transition exiting nodes to the parents new position.
			  var nodeExit = node.exit().transition()
				  .duration(duration'.$numberTree.')
				  .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
				  .remove();
			  nodeExit.select("circle")
				  .attr("r", 1e-10);
			  nodeExit.select("text")
				  .style("fill-opacity", 1e-6);
			  // Update the links
			  var link = svg'.$numberTree.'.selectAll("path.link")
				  .data(links, function(d) { return d.target.id; });
			   // Enter any new links at the parents previous position.
			  var nodeCon=link.enter().insert("path", "g")
				.attr("class", function(d) {
					if (d.target.active == 1) {
						return "link active";
					}
					return "link inactive";
				})
				  .attr("d", function(d) {
					var o = {x: source.x0, y: source.y0};
					return diagonal'.$numberTree.'({source: o, target: o});
				  });
				  
					if(initialUpdate){
					nodeCon.style("stroke", function (d){ 
						if(d.target.active==0){
							return "none";
						}
					});	
				}  
				  
			  // Transition links to their new position.
			  link.transition()
				  .duration(duration'.$numberTree.')
				  .attr("d", diagonal'.$numberTree.');
			  // Transition exiting nodes to the parents new position.
			  link.exit().transition()
				  .duration(duration'.$numberTree.')
				  .attr("d", function(d) {
					var o = {x: source.x, y: source.y};
					return diagonal'.$numberTree.'({source: o, target: o});
				  })
				  .remove();
			  // Stash the old positions for transition.
			  nodes.forEach(function(d) {
				d.x0 = d.x;
				d.y0 = d.y;
			  });
			}
			// Toggle children on click.
			function click'.$numberTree.'(d) {
			  if (d.children) { //replegar
				d._children = d.children;
				d.children = null;
			  } else { //desplegar
				d.children = d._children;
				d._children = null;
			  }
			  update'.$numberTree.'(d,false);
			  }
			  
			function mouseover'.$numberTree.'(d) {
				if(d.active!=1){
					div'.$numberTree.'.transition()
					.duration(300)				
					.style("opacity", 1);
				}
			}

			function mousemove'.$numberTree.'(d) {			
				if(d.active!=1){
					div'.$numberTree.'
					.text(d.name)
					//.attr("class",getColor(d))
					.style("left", (d3.event.pageX ) + "px")
					.style("top", (d3.event.pageY) + "px")
					/*.style("background",getColor(d))
					.style("border-color",getColorBorder(d));*/
					.style("background","#ddd")
					.style("border-color","#ccc");
					
				}			
			}
		
			function mouseout'.$numberTree.'() {
				div'.$numberTree.'.transition()
				.duration(300)
				.style("opacity", 1e-6);
			}
			
			function getNodeName'.$numberTree.'(original,space) {
				var name = "";	
				var separator="";
				if(space){separator=" ";}
				$.each(original.split("="), function( index, value ) {
					name+=value+separator;
				});
				name=$.trim(name);
				return name.replace(" ", ": ");
			}
			  
			 
			</script>
';
return $js;
}


//*************************************************************
        
