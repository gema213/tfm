<?php

/*
TODO
- Generar la lista de listas
- Generar el JSON final
*/
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
//	echo "TREE-----------------------------------------------\n";
//	print_r($tree);
//	echo "NEW RULE-------------------------------------------\n";
//	print_r($new_rule);
//	echo "---------------------------------------------------\n";
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
//				echo "CALL RECURSIVE ---------------------------------------------------------\n";
//				echo $child->name."----".$child->isLeaf."\n";
//				echo "------------------------------------------------------------------------\n";
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

// Get content from a URL
$xml_raw = simplexml_load_string(file_get_contents('http://www.pulso.uniovi.es/moises/rules_all_v2.xml'));

// Transform content to XML
$xml = $xml_raw->asXML();

// Generate a simple XML Element and apply xPath to rules in tree=1
$simple_xml_all = new SimpleXMLElement($xml) ;
$rules = $simple_xml_all -> xpath("//rule[@tree='1']");

// Mock array with actual values for a student
$values = array(
	"RESOURCES" =>"LOW",
	"QUIZZES" =>"LOW",
	"EXTERNALRESOURCES" => "LOW",
	"TIMETOQUIZZES" => "LOW",
	"TIMETOEXTERNALS" => "LOW",
	"TIMETOASSIGNMENTS" => "LOW",
	"TIMETORESOURCES" => "LOW",
	"TIMETOFIRSTACTION" => "LOW"
);

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
		$actual_value = $values["$varia"];
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
//echo "FINAL TREE -------------------------------------------------------------\n";
//print_r($rootNode);
// Generate JSON
$json = json_encode($rootNode, JSON_PRETTY_PRINT);
echo $json;
?>
