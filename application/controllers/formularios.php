<?php

/**
 * 
 */
class Formularios extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }

  public function index(){  
    
  }
  
  //funcion para responder solicitudes AJAX
  public function listarAJAX(){
    //VERIFICAR
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->listar(0,1000);
    foreach ($formularios as $formulario) {
      echo  "$formulario->IdFormulario\t".
            "$formulario->Nombre\t".
            "$formulario->Creacion\t\n";
    }
  }
}

?>