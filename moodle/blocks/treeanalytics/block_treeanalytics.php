<?php
require('jsCreation.php');
require('databaseConnection.php');
require('filesConnection.php');
class block_treeanalytics extends block_base {
	public function init() {
        	$this->title = get_string('treeanalytics', 'block_treeanalytics');
	}
	function has_config(){return true;}
	
	public function get_content() {
		if ($this->content !== null) {
			return $this->content;
		}
	 
		$this->content         =  new stdClass;
		$this->content->text   = '<script src="http://d3js.org/d3.v3.min.js"></script>';
		$this->content->text.=style();
		/**/		
		$iniFile=iniFile();
		$this->content->text.='<br>';	
		
		while(list($key, $value)=each($iniFile)){
//			$this->content->text.=$key.'-'.$value.'<br>';
		}

		$xmlFile=xmlFile();
		$this->content->text.=$xmlFile__toString ;
		/**/
		$this->content->text.='<br>

			<!-- Button trigger modal -->
			<div class="container">
				<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Watch tree</button> 
				<br>
				<button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#myModal">Click to open Modal</button>
			</div>	

			<!-- Modal1 -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Tree Analytics</h4>
						</div>
						<div class="modal-body">
							<script src="http://156.35.95.149/moodle/blocks/treeanalytics/tree.js"/>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal2 -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="largeModalLabel">Modal title</h4>
						</div>
						<div class="modal-body">
							...
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>

	';  
	return $this->content;
	}
}  
