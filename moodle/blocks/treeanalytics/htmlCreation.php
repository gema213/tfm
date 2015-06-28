<?php

function externalScripts() {
	return '
		
		<script src="http://static.scripting.com/bootstrap/1.4.0/js/bootstrap-tabs.js"></script>
<!--		<script src="http://static.scripting.com/bootstrap/1.4.0/js/bootstrap-modal.js"></script> -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->

		<script src="http://d3js.org/d3.v3.min.js"></script>
';
}
function style(){			return '
		<style type="text/css">
			.container{
				margin-left: auto;
				margin-right: auto;
				width: 70%;
			}

			/*TABS STYLE*/
			.tabs{
				padding: 3px 0;
				margin-left: 0;
				border-bottom: 1px solid #778;
				font: bold 12px Verdana, sans-serif;
			}

			.tabs li{
				display: inline;
				list-style: none;
				margin:0;
			}
			
			.tabs li a{
				padding: 3px 0.5em;
				margin-left: 3px;
				border: 1px solid #778;
				border-bottom: none;
				background: #DDE;
				text-decoration: none;
			}

			
			/*TREE STYLE*/

			.node circle {stroke-width: 3px;}

			.node	.inactive{fill:#ddd;stroke:#ccc;}
			.node	.active{fill:#00C4C4;stroke:blue;}
			.node .good{fill:#00D600;stroke:#04B404;}/*green*/
			.node .pass{fill:#FE9A2E;stroke:#FF4000;}/*orange*/
			.node .fail{fill:#D60000;stroke:#610B0B;}/*red*/

			.node text {font: 12px sans-serif; }
			.node  .off {fill:gray; display:none;}
			.node .on{fill:black;}

			.link {
			  fill: none;
			  stroke: #ccc;
			  stroke-width: 2px;
			}

			.link.active{
				stroke:#333;
			}
			
			div.tooltip {
		                position: absolute;
		                text-align: center;
		                padding: 20px;
		                font: 10px bold; 				
		                border: solid 3px;
		                border-radius: 5px;				
			}

		</style>
	';
}
