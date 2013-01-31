<?php

/**
 * 
 */
class Usuarios extends CI_Controller {
	
	const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
	  parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
	}
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Recibir formulario de inicio de sesión.
   */
  function login(){
    //si no recibimos ningún valor proveniente del formulario (el usuario no ingresó sus datos)
    if($this->input->post('Usuario')==FALSE){
      //pantalla del formulario de ingreso (pantalla de inicio)
      $data['usuarioLogin'] = $this->ion_auth->user()->row();
      $this->load->view('index', $data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('Usuario','Nombre de usuario','required|alpha_numeric');      
      $this->form_validation->set_rules('Contrasena','Contraseña','required|alpha_numeric');
      if($this->form_validation->run()==true){
        //en caso de que los datos sean correctos, realizo login
        if ($this->ion_auth->login($this->input->post('Usuario'), $this->input->post('Contrasena'), (bool) $this->input->post('Recordarme'))){
          //si el usuario ingresó datos de acceso válidos
          $data['usuarioLogin'] = $this->ion_auth->user()->row();
          $this->load->view('index', $data);
        }
        else{
          echo'no';
          //si no logró validar
          $data['mensajeLogin']="Nombre de usuario y/o contraseña inválidos, por favor vuelva a intentar.";
          $this->load->view('index', $data);
        }
      }
      else{
        //en caso de que los datos sean incorrectos
        $data['mensajeLogin']="Nombre de usuario y/o contraseña inválidos, por favor vuelva a intentar.";
        $this->load->view('index', $data);
      }
    }
  }

  /*
   * Cerrar sesión.
   */
  function logout(){
    $logout = $this->ion_auth->logout();
    $this->load->view('index');
  }
  
  /*
   * Muestra el listado de docentes y autoridades.
   */
  public function listar($PagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gp');

    //obtengo lista de departamentos
    $Usuarios = $this->gp->listar($PagInicio, self::per_page);
    $tabla = array();
    foreach ($Usuarios as $i => $Usuario) {
      $tabla[$i]=array(
        'IdUsuario' => $Usuario->IdUsuario,
        'Apellido' => $Usuario->Apellido,
        'Nombre' => $Usuario->Nombre,
       );
    }
       
    //genero la lista de links de paginación
    $config['base_url'] = site_url("Usuarios/listar");
    $config['total_rows'] = $this->gp->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('lista_Usuarios', $data);
  }
  
  /*
   * Ver y editar datos relacionados a una Usuario
   */
  public function ver($IdUsuario=null, $PagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    $IdUsuario = (int)$IdUsuario;
    
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gp');
    
    //obtengo datos del departamento
    $Usuario = $this->gp->dame($IdUsuario);
    if ($Usuario){
      $data['Usuario'] = array(
        'IdUsuario' => $Usuario->IdUsuario,
        'Apellido' => $Usuario->Apellido,
        'Nombre' => $Usuario->Nombre
      );
      //envio datos a la vista
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $this->load->view('ver_Usuario', $data);
    }
    else{
      show_error('El Identificador de Docente/Autoridad no es válido.');
    }
  }
  


  /*
   * Recepción del formulario para modificar los datos de una Usuario
   * POST: Usuario, Apellido, Nombre
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space');
    $this->form_validation->set_rules('username','Nombre de usuario','alpha_dash_space|required');
    $this->form_validation->set_rules('email','Apellido','alpha_dash_space|required');
    $this->form_validation->set_rules('Apellido','Apellido','alpha_dash_space|required');
    $this->form_validation->set_rules('Apellido','Apellido','alpha_dash_space|required');
    
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_Usuarios','gp');
      $IdUsuario = $this->input->post('IdUsuario',TRUE);
      $Nombre = $this->input->post('Nombre',TRUE);
      
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gp->modificar($IdUsuario, $this->input->post('Apellido',TRUE), ($Nombre=='')?NULL:$Nombre);
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("Usuarios/ver/$IdUsuario"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina del departamento
      $this->ver($this->input->post('IdUsuario',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una Usuario
   * POST: IdUsuario
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdUsuario','ID Usuario','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Gestor_Usuarios','gp');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gp->baja($this->input->post('IdUsuario',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("Usuarios"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  //funcion para responder solicitudes AJAX
  public function buscarAJAX(){
    $buscar = $this->input->post('Buscar');
    //VERIFICAR
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gp');
    $Usuarios = $this->gp->buscar($buscar);
    echo "\n";
    foreach ($Usuarios as $Usuario) {
      echo  "$Usuario->IdUsuario\t".
            "$Usuario->Apellido\t".
            "$Usuario->Nombre\t\n";
    }
  }
  
  
  public function tmp(){
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('tmp',$data);
  }
  
}

?>