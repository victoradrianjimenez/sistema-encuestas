<?php

/**
 * 
 */
class Formularios extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }

  public function index(){  
    
  }
  
  //funcion para responder solicitudes AJAX
  public function buscarAJAX(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->buscar($buscar);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->IdFormulario\t".
            "$formulario->Nombre\t".
            "$formulario->Creacion\t\n";
    }
  }
  
  //funcion para responder solicitudes AJAX
  public function listarAJAX(){
    //VERIFICAR
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->listar(0,1000);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->IdFormulario\t".
            "$formulario->Nombre\t".
            "$formulario->Creacion\t\n";
    }
  }
}

?>