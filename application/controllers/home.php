<?php

/**
 * 
 */
class Home extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }

  public function index(){  
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('index', $data);
  }

  public function enlaces(){  
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('enlaces', $data);
  }
  
  public function contacto(){  
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('contacto', $data);
  }
  
  public function tmp(){
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('editar_formulario', $data);
  }
  
}

?>