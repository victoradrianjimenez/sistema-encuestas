<?php

/**
 * 
 */
class Personas extends CI_Controller {
	
	function __construct() {
	  parent::__construct();
	}
  
  public function index(){
    $this->listar();
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
  
  
  public function listar($pagInicio=0){
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
        'Usuario' => $persona->Usuario,
        'Email' => $persona->Email,
        'UltimoAcceso' => $persona->UltimoAcceso,
        'Estado' => $persona->Estado
       );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_personas', $data);
  }
  
  
  public function nueva(){
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!

    //si no recibimos ningún valor proveniente del formulario
    if(!$this->input->post('submit')){
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
      $data['persona'] = array(
        'IdPersona' => 0,
        'Apellido' => '',
        'Nombre' => '',
        'Usuario' => '',
        'Email' => '',
        'Contraseña' => ''
      );
      $data['link'] = site_url("personas/nueva"); //hacia donde mandar los datos      
      $this->load->view('ver_persona',$data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('Apellido','Apellido','alpha|required');
      $this->form_validation->set_rules('Nombre','Nombre','alpha');
      $this->form_validation->set_rules('Usuario','Nombre de usuario','alpha_dash|required');
      $this->form_validation->set_rules('Email','Dirección de correo electrónico','valid_email|required');
      $this->form_validation->set_rules('Contrasena','Contraseña','alpha_numeric|required'); //ya viene hasheado
      $this->form_validation->set_rules('Contrasena2','Repetición de contraseña','matches[Contrasena]');
      $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos, cargo el formulario nuevamente
        $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
        $data['persona'] = array(
          'IdPersona' => 0,
          'Apellido' => $this->input->post('Apellido'),
          'Nombre' => $this->input->post('Nombre'),
          'Usuario' => $this->input->post('Usuario'),
          'Email' => $this->input->post('Email'),
          'Contraseña' => ''
        );
        $data['link'] = site_url("personas/nueva"); //hacia donde mandar los datos
        $this->load->view('ver_persona',$data);
      }
      else{
        //agrego departamento y cargo vista para mostrar resultado
        $this->load->model('Gestor_personas','gp');
        $res = $this->gp->alta($this->input->post('Apellido',TRUE), $this->input->post('Nombre',TRUE),
                               $this->input->post('Usuario',TRUE), $this->input->post('Email',TRUE), 
                               $this->input->post('Contrasena',TRUE));
        $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
        $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":$res;
        $data['link'] = site_url("personas"); //hacia donde redirigirse
        $this->load->view('resultado_operacion', $data);
      }
    }
  }

  //funcion para responder solicitudes AJAX
  public function buscar(){
    $buscar = $this->input->post('Buscar');
    //VERIFICAR
    $this->load->model('Persona');
    $this->load->model('Gestor_personas','gp');
    $personas = $this->gp->buscar($buscar);
    foreach ($personas as $persona) {
      echo  "$persona->IdPersona\t".
            "$persona->Apellido\t".
            "$persona->Nombre\t".
            "$persona->Usuario\t\n";
    }
  }
  
  
  public function tmp(){
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
    $this->load->view('tmp',$data);
  }
  
}

?>