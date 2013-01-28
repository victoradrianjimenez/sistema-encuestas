<?php

/**
 * 
 */
class Devoluciones extends CI_Controller {
    
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
	function __construct() {
		parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
	}
  
  public function index(){
    
  }
  
}
