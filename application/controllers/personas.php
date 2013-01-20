<?php

/**
 * 
 */
class Personas extends CI_Controller {
	
	function __construct() {
	  parent::__construct();
	}
  
  public function index(){
    $this->listarUsuarios();
  }
  
  function login(){
    //si no recibimos ningún valor proveniente del formulario (el usuario no ingresó sus datos)
    if($this->input->post('Usuario')==FALSE){
      //pantalla del formulario de ingreso (pantalla de inicio)
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
      $this->load->view('index', $data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('Usuario','Nombre de usuario','required|alpha_numeric');      
      $this->form_validation->set_rules('Contrasena','Contraseña','required|alpha_numeric');
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos
        $data['mensajeLogin']="Nombre de usuario y/o contraseña inválidos, por favor vuelva a intentar.";
        $this->load->view('index', $data);
      }
      else{
        //en caso de que los datos sean correctos, verifico si existe el usuario
        $this->load->model('Persona');
        $this->load->model('Gestor_personas');
        $p = $this->Gestor_personas->validarUsuario($this->input->post('Usuario'), SHA1($this->input->post('Contrasena')));
        if($p){
          //si el usuario ingresó datos de acceso válidos
          $data['usuarioLogin']=$p;
          $this->session->set_userdata('usuarioLogin', serialize($p));
          $this->load->view('index', $data);
        }
        else{
          //si no logró validar
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
  
  
  public function listarUsuarios($pagInicio=0){
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Persona');
    $this->load->model('Gestor_personas','gp');
       
    //genero la lista de links de paginación
    $config['base_url'] = site_url("personas/listar");
    $config['total_rows'] = $this->gp->cantidad();
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de departamentos
    $personas = $this->gp->listar($pagInicio, $config['per_page']);
    $tabla = array();
    foreach ($personas as $i => $persona) {
      $tabla[$i]=array(
        'IdPersona' => $persona->IdPersona,
        'Apellido' => $persona->Apellido,
        'Nombre' => $persona->Nombre,
        'Email' => $persona->Email,
        'UltimoAcceso' => $persona->UltimoAcceso,
        'Estado' => $persona->Estado
       );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuario'] = unserialize($this->session->userdata('usuario')); //objeto Persona (usuario logueado)
    $this->load->view('lista_usuarios', $data);
  }
  
  
 
}

?>