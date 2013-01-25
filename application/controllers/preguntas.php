<?php

/**
 * 
 */
class Preguntas extends CI_Controller {
	
	function __construct() {
	  parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
	}
  
  public function index(){
    
  }
  
}

?>