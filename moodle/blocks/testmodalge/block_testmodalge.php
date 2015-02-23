<?php
class block_testmodalge extends block_base {
    public function init() {
        $this->title = get_string('testmodalge', 'block_testmodalge');
    }
	
	public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }
 
    $this->content         =  new stdClass;
    $this->content->text   = '
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
-->
<!-- Button HTML (to Trigger Modal) -->
   <a href="#myModal" class="btn btn-lg btn-primary" data-toggle="modal">Launch Demo Modal</a>
    
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Test Modal</h4>
                </div>
                <div class="modal-body">
                    <p>Meeeeeeeh</p>
                 
                </div>
                
            </div>
        </div>
    </div>
';

    return $this->content;
  }
}
