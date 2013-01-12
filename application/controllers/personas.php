<?php

/**
 * 
 */
class Personas extends CI_Controller {
	
	function __construct() {
	  parent::__construct();
	}
  
  public function index(){
    
  }
  
  function login(){
    logout();
    //si no recibimos ningún valor proveniente del formulario (el usuario no ingresó sus datos)
    if($this->input->post('usuario')==FALSE){
      //pantalla del formulario de ingreso (pantalla de inicio)
      $data = null;
      $this->load->view('index', $data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('usuario','Nombre de usuario','required|alpha_numeric');      
      $this->form_validation->set_rules('contrasena','Contraseña','required|alpha_numeric');
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos
        $data['estadoLogin']="error"; 
        $data['mensajeLogin']="Nombre de usuario y/o contraseña inválidos, por favor vuelva a intentar.";
        $this->load->view('index', $data);
      }
      else{
        //en caso de que los datos sean correctos, verifico si existe el usuario
        $this->load->model('Persona');
        $this->load->model('Gestor_personas');
        $p = $this->Gestor_personas->validarUsuario($this->input->post('usuario'), SHA1($this->input->post('contrasena')));
        if($p){
          //si el usuario ingresó datos de acceso válidos
          $data['estadoLogin']="ok";
          $data['usuario']=$p;
          $this->session->set_userdata('usuario', serialize($p));
          $this->load->view('index', $data);
        }
        else{
          //si no logró validar
          $data['estadoLogin']="error"; 
          $data['mensajeLogin']="Nombre de usuario y/o contraseña incorrecta, por favor vuelva a intentar.";
          $this->load->view('index',$data);
        }
      }
    }
  }
  function logout(){
    $this->session->sess_destroy();
    $this->load->view('index');
  }
  
}

?>