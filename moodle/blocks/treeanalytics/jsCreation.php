<?php
function style() {
	return '
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!--
<link rel="stylesheet" href="http://156.35.95.149/moodle/blocks/treeanalytics/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://156.35.95.149/moodle/blocks/treeanalytics/bootstrap/js/bootstrap.min.js"></script>-->

		<style type="text/css">
			#myModal{
				width:auto; //or 100% or fixed size
			}
			.node { cursor: pointer;}
			
			.node circle { stroke-width: 3px;}*/
/*
			.node	.inactive{fill:#ddd;stroke:#ccc;}
			.node	.active{fill:aqua;stroke:blue;}
			.node .good{fill:#00FF00;stroke:#04B404;}/*green*/
			.node .pass{fill:#FE9A2E;stroke:#FF4000;}/*orange*/
			.node .fail{fill:#FF0000;stroke:#610B0B;}/*red*/
			.node .unknown {fill:black;stroke:black;}
*/			
			.node text {color:green;  font: 12px sans-serif; }
			.node .off {fill:gray;}
			.node text.on{fill:black;}

			.link { fill: none;	stroke: #ccc;stroke-width: 2px;}
			
			/**/
			.link.active{
				stroke:#333;
			}
	
		</style>
	';
}
