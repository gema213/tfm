<?php
require_once('jsCreation.php');
require_once('htmlCreation.php');
class block_treeanalytics extends block_base {
	public function init() {
        	$this->title = get_string('treeanalytics', 'block_treeanalytics');
	}
	function has_config(){return true;}
	
	public function get_content(){
		global $CFG, $USER, $COURSE,$DB;       
		require_once($CFG->dirroot.'/message/lib.php');
		if ($this->content !== null) {
			return $this->content;
		}
	 
		$this->content         =  new stdClass;
		$this->content->text   = externalScripts();
		$this->content->text.=style();
		if($USER->id==0){
			$this->content->text.=get_string('loginRequired', 'block_treeanalytics');
		}else{
			if($USER->id==1){
				$this->content->text.=get_string('signupRequired', 'block_treeanalytics');
			}else{
				if($COURSE->id==1){
					 $this->content->text.=get_string('courseRequired', 'block_treeanalytics');
				}else{
					$role = $DB->get_record('role', array('shortname' => 'student'));
					$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
					$students = get_role_users($role->id, $context);
					if(!array_key_exists($USER->id, $students)){
						$this->content->text.=get_string('onlyStudents', 'block_treeanalytics');
					}else{
					
						$studentValues=generateStudentValues($this->config);

						$this->content->text.='
						<div id="actualStatus">
						<h2>'.get_string('actualStatus','block_treeanalytics').'</h2>
						<ul>
						<li>'.get_string('quizzes', 'block_treeanalytics').': '.get_string(strtolower($studentValues["QUIZZES"]), 'block_treeanalytics').'</li>
						<li>'.get_string('resources', 'block_treeanalytics').': '.get_string(strtolower($studentValues["RESOURCES"]), 'block_treeanalytics').'</li>
						<li>'.get_string('recommendedresources', 'block_treeanalytics').': '.get_string(strtolower($studentValues["RECOMMENDEDRESOURCES"]), 'block_treeanalytics').'</li>
						<li>'.get_string('timetoquizzes', 'block_treeanalytics').': '.get_string(strtolower($studentValues["TIMETOQUIZZES"]), 'block_treeanalytics').'</li>
						<li>'.get_string('timetoresources', 'block_treeanalytics').': '.get_string(strtolower($studentValues["TIMETORESOURCES"]), 'block_treeanalytics').'</li>
						<li>'.get_string('timetoassignments', 'block_treeanalytics').': '.get_string(strtolower($studentValues["TIMETOASSIGNMENTS"]), 'block_treeanalytics').'</li>
						<li>'.get_string('timetorecommended', 'block_treeanalytics').': '.get_string(strtolower($studentValues["TIMETORECOMMENDED"]), 'block_treeanalytics').'</li>
						<li>'.get_string('timetofirstaction', 'block_treeanalytics').': '.get_string(strtolower($studentValues["TIMETOFIRSTACTION"]), 'block_treeanalytics').'</li>
						</ul>
						</div>';
						
					
												$this->content->text.='
						<div class="container">
						  
							  <a href="#" id="tree" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#largeModal">'.get_string('treeanalytics', 'block_treeanalytics').'</a>
						 
						</div>
						<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
						  <div class="modal-dialog modal-lg">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">'.get_string('treeanalytics', 'block_treeanalytics').'</h4>
							  </div>
							  <div class="modal-body tree">
								<div class="divDialogElements">
												<ul class="tabs" data-tabs="tabs">
													<li class="active"><a href="#tree1">'.get_string('tree', 'block_treeanalytics').' 1</a></li>
													<li><a href="#tree2">'.get_string('tree', 'block_treeanalytics').' 2</a></li>
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
												</div>
								</div>	
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">'.get_string('close', 'block_treeanalytics').'</button>
							  </div>
							</div>
						  </div>
						</div>				
							';  
					}
				}
			}
		}
		return $this->content;
	}
} 


