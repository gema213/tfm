<?php

require('jsCreation.php');
require('htmlCreation.php');
class block_treeanalytics extends block_base {

	public function init() {

        	$this->title = get_string('treeanalytics', 'block_treeanalytics');
	}
	function has_config(){return true;}
	
	public function get_content(){

		global $CFG, $USER, $COURSE;       
		require_once($CFG->dirroot.'/message/lib.php');

		if ($this->content !== null) {
			return $this->content;
		}
	 
		$this->content         =  new stdClass;
		$this->content->text   = externalScripts();
		$this->content->text.=style();
//		$studentValues=generateRandomStudentValues();	
$studentValues=generateStudentValues();

		if($USER->id==0){
			$this->content->text.='Debe iniciar sesión para visualizar información en este bloque';
		}else{
			if($USER->id==1){
				$this->content->text.='Debe iniciar sesión con un usuario registrado para visualizar información en este bloque';
			}else{
				if($COURSE->id==1){
					 $this->content->text.='Debe acceder a un curso para visualizar informacion de este bloque';
				
				}else{	
$this->content->text.='User: '.$USER->id.' - '.$USER->username.'<br>';
$this->content->text.='Course: '.$COURSE->id.' - '.$COURSE->fullname.'<br><br>';
$studentValues2=generateStudentValues();

$this->content->text.='

QUIZZES: '.$studentValues2["QUIZZES"].'
<br>RESOURCES: '.$studentValues2["RESOURCES"].'
<br>RECOMMENDEDRESOURCES: '.$studentValues2["RECOMMENDEDRESOURCES"].'
<br>TIMETOQUIZZES: '.$studentValues2["TIMETOQUIZZES"].'
<br>TIMETORESOURCES: '.$studentValues2["TIMETORESOURCES"].'
<br>TIMETOASSIGNMENTS: '.$studentValues2["TIMETOASSIGNMENTS"].'
<br>TIMETORECOMMENDED: '.$studentValues2["TIMETORECOMMENDED"].'
<br>TIMETOFIRSTACTION: '.$studentValues2["TIMETOFIRSTACTION"];

$this->content->text.='<br><br>';
				$this->content->text.='
<div class="container">
  
      <a href="#" id="tree" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#largeModal">Tree Analytics</a>
  
<br><br> 
      <a href="#" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#basicModal">Summary Diagram</a>
  
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Tree Analytics</h4>
      </div>
      <div class="modal-body tree">
		<div class="divDialogElements">
						<ul class="tabs" data-tabs="tabs">
							<li class="active"><a href="#tree1">Tree1</a></li>
							<li><a href="#tree2">Tree2</a></li>
							<li><a href="#tree3">Tree3</a></li>							
							</ul>
						<div id="my-tab-content" class="tab-content">
							<div class="active tab-pane" id="tree1">
								<p>';
$this->content->text.=createJS(1,$studentValues);
$this->content->text.='</p>
								</div>
							<div class="tab-pane" id="tree2">
								<p>';

$this->content->text.=createJS(2,$studentValues);
$this->content->text.='</p>
								</div>
							<div class="tab-pane" id="tree3">
								<p>Otra pestaña!!</p>
								</div>
							</div>
						</div>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
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
      </div>
    </div>
  </div>
</div>
	';  
}}}
	return $this->content;
	}
} 
