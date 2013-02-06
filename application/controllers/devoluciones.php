<?php

/**
 * 
 */
class Devoluciones extends CI_Controller {
    
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado

  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
  }
  
  public function index(){
    
  }
  
  /*
   * Muestra el formulario de edicion de formularios
   * Última revisión: 2012-02-05 7:36 p.m.
   */
  public function editar(){
    $this->load->view('editar_devolucion', $this->data);
  }
  
  
}
