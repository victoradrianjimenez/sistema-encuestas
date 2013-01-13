<?php

/**
 * 
 */
class Claves extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    
  }
  
  public function ingresar(){
    //verifico si se envio clave
    $pClave = $this->input->post('clave');
    if($pClave){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      //busco la clave ingresada
      $clave = $this->Encuesta->buscarClave($pClave);
      if ($clave){
        //si la clave no fue utilizada
        if ($clave->Utilizada == ''){
          $data = '';
          $this->load->view('formulario_encuesta', $data);
        }
        else{
          $data['clave'] = $pClave;
          $data['mensaje'] = "Clave de Acceso Utilizada el $clave->Utilizada.";
          $this->load->view('ingreso_clave', $data);
        }
      }
      else{
        $data['clave'] = $pClave;
        $data['mensaje'] = 'Clave de Acceso Inválida';
        $this->load->view('ingreso_clave', $data);
      }
    }
    else{
      $data['clave'] = '';
      $data['mensaje'] = '';
      $this->load->view('ingreso_clave', $data);
    }
    
  }
   
}

?>