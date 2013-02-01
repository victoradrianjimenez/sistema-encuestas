<?php

/**
 * 
 */
class Alumnos extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //datos de session para enviarse a las vistas
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row(); 
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>');     
  }
  
  public function index(){
    
  }
  
}

?>