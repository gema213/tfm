<?php 
class block_testdbge extends block_base {
	 public function init() {
        	$this->title = get_string('testdbge', 'block_testdbge');
	}
	
	public function get_content() {
		if ($this->content !== null) {
			return $this->content;
		}

//

global $DB;

$users= $DB->get_records_sql('SELECT * FROM {user}');
//$user=$DB->get_record('user');


$courses=$DB->get_records_sql('SELECT * FROM {course}');


 
$this->content         =  new stdClass;
if($users==null || sizeof($users)==0){
	$this->content->text='no data';
}else{
	$this->content->text='Num users:'.sizeof($users);
//	for($i=0;$i<sizeof($user);$i++){
foreach($users as $user){
//		$this->content->text.='<br>*'.$user[$i]->id.' '.$user[$i]->username.' '.$user[$i]->email;
$this->content->text.='<br>*'.$user->id.' '.$user->username.' '.$user->email;
	}

}

$this->content->text.='<br><br> Num courses:'.sizeof($courses);
//for($i=0;$i<sizeof($course);$i++){
foreach($courses as $course){
$this->content->text.='<br>*'.$course->fullname;
/*foreach($course as $data){
$this->content->text.='*'.$data;//$course->id.' '.$course->name;
}*/
}


 
    return $this->content;
  }
}
