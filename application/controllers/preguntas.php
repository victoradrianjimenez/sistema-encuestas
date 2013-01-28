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
  
  
  //funcion para responder solicitudes AJAX
  public function buscarAjax(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $preguntas = $this->gp->buscar($buscar);
    echo "\n";
    foreach ($preguntas as $pregunta) {
      echo  "$pregunta->IdPregunta\t".
            "$pregunta->IdCarrera\t".
            "$pregunta->Texto\t".
            "$pregunta->Creacion\t".
            "$pregunta->Tipo\t".
            "$pregunta->Obligatoria\t\n";
    }
  }
  
  
}

?>