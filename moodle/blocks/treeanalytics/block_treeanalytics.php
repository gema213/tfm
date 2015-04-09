<?php
require('jsCreation.php');
require('htmlCreation.php');
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
//		$this->content->text.=style();
		$this->content->text.=createJSON();
		/**/		
		$this->content->text.='<br>';	
	$this->content->text.= getStudentValues();	
	$this->content->text.='
<div class="container">
  
      <a href="#" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#largeModal">Tree Analytics</a>
  
<br><br> 
      <a href="#" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#basicModal">Resume Diagram</a>
  
</div>
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Basic Modal</h4>
      </div>
      <div class="modal-body">
        <h3>Modal Body</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Tree Analytics</h4>
      </div>
      <div class="modal-body tree">';
$this->content->text.=createJS();
$this->content->text.='
      </div>
<!--      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
</div>	';  
	return $this->content;
	}
} 
