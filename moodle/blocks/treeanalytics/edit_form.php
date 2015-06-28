<?php 
	class block_treeanalytics_edit_form extends block_edit_form {	 
		protected function specific_definition($mform) {
			require_once('filesConnection.php');
			global $CFG,$COURSE;
			require_once($CFG->dirroot.'/message/lib.php');
			
			$actualCourse=$COURSE->id;
			$iniFile=iniFile(); 
			
			// Section header title according to language file.
			$mform->addElement('header', 'configheader', get_string('rankValues', 'block_treeanalytics')); 
			//Quizzes LOW
			$mform->addElement('text', 'config_quizzeslow'.$actualCourse, get_string('quizzes', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_quizzeslow'.$actualCourse, $iniFile['threshold.quizzes.low']);
			$mform->setType('config_quizzeslow'.$actualCourse, PARAM_INT);
			//Quizzes HIGH
			$mform->addElement('text', 'config_quizzeshigh'.$actualCourse, get_string('quizzes', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_quizzeshigh'.$actualCourse, $iniFile['threshold.quizzes.high']);
			$mform->setType('config_quizzeshigh'.$actualCourse, PARAM_INT);
			//Resources LOW
			$mform->addElement('text', 'config_resourceslow'.$actualCourse, get_string('resources', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_resourceslow'.$actualCourse, $iniFile['threshold.resources.low']);
			$mform->setType('config_resourceslow'.$actualCourse, PARAM_INT);
			//Resources HIGH
			$mform->addElement('text', 'config_resourceshigh'.$actualCourse, get_string('resources', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_resourceshigh'.$actualCourse, $iniFile['threshold.resources.high']);
			$mform->setType('config_resourceshigh'.$actualCourse, PARAM_INT);
			//Recommended Resources LOW
			$mform->addElement('text', 'config_recommendedresourceslow'.$actualCourse, get_string('recommendedresources', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_recommendedresourceslow'.$actualCourse, $iniFile['threshold.recommendedresources.low']);
			$mform->setType('config_recommendedresourceslow'.$actualCourse, PARAM_INT);
			//Recommended Resources HIGH
			$mform->addElement('text', 'config_recommendedresourceshigh'.$actualCourse, get_string('recommendedresources', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_recommendedresourceshigh'.$actualCourse, $iniFile['threshold.recommendedresources.high']);
			$mform->setType('config_recommendedresourceshigh'.$actualCourse, PARAM_INT);
			//Time To Quizzes LOW
			$mform->addElement('text', 'config_timetoquizzeslow'.$actualCourse, get_string('timetoquizzes', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_timetoquizzeslow'.$actualCourse, $iniFile['threshold.timetoquizzes.low']);
			$mform->setType('config_timetoquizzeslow'.$actualCourse, PARAM_INT);
			//Time To Quizzes HIGH
			$mform->addElement('text', 'config_timetoquizzeshigh'.$actualCourse, get_string('timetoquizzes', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_timetoquizzeshigh'.$actualCourse,$iniFile['threshold.timetoquizzes.high']);
			$mform->setType('config_timetoquizzeshigh'.$actualCourse, PARAM_INT);
			//Time To Resources LOW
			$mform->addElement('text', 'config_timetoresourceslow'.$actualCourse, get_string('timetoresources', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_timetoresourceslow'.$actualCourse, $iniFile['threshold.timetoresources.low']);
			$mform->setType('config_timetoresourceslow'.$actualCourse, PARAM_INT);
			//Time To Resources HIGH
			$mform->addElement('text', 'config_timetoresourceshigh'.$actualCourse, get_string('timetoresources', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_timetoresourceshigh'.$actualCourse, $iniFile['threshold.timetoresources.high']);
			$mform->setType('config_timetoresourceshigh'.$actualCourse, PARAM_INT);
			//Time To Assignments LOW
			$mform->addElement('text', 'config_timetoassignmentslow'.$actualCourse, get_string('timetoassignments', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_timetoassignmentslow'.$actualCourse, $iniFile['threshold.timetoassignments.low']);
			$mform->setType('config_timetoassignmentslow'.$actualCourse, PARAM_INT);
			//Time To Assignments HIGH
			$mform->addElement('text', 'config_timetoassignmentshigh'.$actualCourse, get_string('timetoassignments', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_timetoassignmentshigh'.$actualCourse, $iniFile['threshold.timetoassignments.high']);
			$mform->setType('config_timetoassignmentshigh'.$actualCourse, PARAM_INT);
			//Time To Recommended LOW
			$mform->addElement('text', 'config_timetorecommendedlow'.$actualCourse, get_string('timetorecommended', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_timetorecommendedlow'.$actualCourse,$iniFile['threshold.timetorecommended.low']);
			$mform->setType('config_timetorecommendedlow'.$actualCourse, PARAM_INT);
			//Time To Recommended HIGH
			$mform->addElement('text', 'config_timetorecommendedhigh'.$actualCourse, get_string('timetorecommended', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_timetorecommendedhigh'.$actualCourse, $iniFile['threshold.timetorecommended.high']);
			$mform->setType('config_timetorecommendedhigh'.$actualCourse, PARAM_INT);
			//Time To First Action LOW
			$mform->addElement('text', 'config_timetofirstactionlow'.$actualCourse, get_string('timetofirstaction', 'block_treeanalytics').' '.get_string('low', 'block_treeanalytics'));
			$mform->setDefault('config_timetofirstactionlow'.$actualCourse, $iniFile['threshold.timetofirstaction.low']);
			$mform->setType('config_timetofirstactionlow'.$actualCourse, PARAM_INT);
			//Time To First Action HIGH
			$mform->addElement('text', 'config_timetofirstactionhigh'.$actualCourse, get_string('timetofirstaction', 'block_treeanalytics').' '.get_string('high', 'block_treeanalytics'));
			$mform->setDefault('config_timetofirstactionhigh'.$actualCourse, $iniFile['threshold.timetofirstaction.high']);
			$mform->setType('config_timetofirstactionhigh'.$actualCourse, PARAM_INT);
		}
	}
