<?php

/**
 * 
 */
class Usuarios extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct(){
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    $this->load->helper('url');
    //datos de session para enviarse a las vistas
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row(); 
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); 
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de usuarios.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Gestor_usuarios','gu');

    //obtengo lista de usuarios
    $usuarios = $this->gu->listar($pagInicio, self::per_page);
    $tabla = array(); //datos para mandar a la vista
    foreach ($usuarios as $i => $usuario) {
      $tabla[$i]['id'] = $usuario->id;
      $tabla[$i]['apellido'] = $usuario->apellido;
      $tabla[$i]['nombre'] = $usuario->nombre;
      $tabla[$i]['email'] = $usuario->email;
      $tabla[$i]['active'] = $usuario->active;
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("usuarios/listar");
    $config['total_rows'] = $this->gu->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $this->data['tabla'] = &$tabla; //array de datos de los Departamentos
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->data['grupos'] = $this->ion_auth->groups()->result_array();
    $this->load->view('lista_usuarios', $this->data);
  }
  
  /*
   * Ver y editar datos relacionados a una Usuario
   */
  public function ver($id=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $id = (int)$id;
    
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gp');
    
    //obtengo datos del departamento 
    if ($id){
      $usuario =  $this->ion_auth->user($id)->row();
      if ($usuario){
        $this->data['usuario'] = array(
          'id' => $usuario->id,
          'apellido' => $usuario->apellido,
          'nombre' => $usuario->nombre,
          'username' => $usuario->username,
          'email' => $usuario->email,
          'active' => $usuario->active,
          'last_login' => date('d/m/Y G:i:s', $usuario->last_login),
          'grupos' => $this->ion_auth->get_users_groups($id)->result_array()
        );
        //envio datos a la vista
        
        $this->data['grupos'] = $this->ion_auth->groups()->result_array();
        $this->load->view('ver_usuario', $this->data);
      }
      else{
        show_error('El Identificador de usuario no es válido.');
      }
    }
    else{
      show_error('El Identificador de usuario no es válido.');
    }
  }
  
  /*
   * Recepción del formulario para agregar nuevo departamento
   * POST: apellido, nombre, username, password, email
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //leo los grupos a los que pertenece el nuevo usuario
    $entradas = $this->input->post(NULL, TRUE);
    $i = 0;
    $grupos = array();
    foreach ($entradas as $key => $b) {
      if (strpos($key, 'grupo') !== false && (bool)$b) {
        sscanf($key, "grupo_%d",$x);
        $grupos[$i++] = $x;
      }
    }
    //verifico datos POST
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_numeric');
    $this->form_validation->set_rules('email','Apellido','required|valid_email');
    $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password2]');
    $this->form_validation->set_rules('password2','Confirmar contraseña','required');    
    if($this->form_validation->run()){      
      $username = $this->input->post('username',TRUE);
      $password = $this->input->post('password',TRUE);
      $email = $this->input->post('email',TRUE);
      $additional_data = array(
        'apellido' => $this->input->post('apellido',TRUE), 
        'nombre' => $this->input->post('nombre',TRUE)
      );
      $res = $this->ion_auth->register($username, $password, $email, $additional_data, $grupos);
      $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":'Se produjo un error al registrar usuario.';
      $this->data['link'] = site_url("usuarios/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de una Usuario
   * POST: id, apellido, nombre, username, password, email
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //leo los grupos a los que pertenece el nuevo usuario
    $entradas = $this->input->post(NULL, TRUE);
    $i = 0;
    $grupos = array();
    foreach ($entradas as $key => $b) {
      if (strpos($key, 'grupo') !== false && (bool)$b) {
        sscanf($key, "grupo_%d",$x);
        $grupos[$i++] = $x;
      }
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Identificador','is_natural_no_zero|required');
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_numeric');
    $this->form_validation->set_rules('email','Apellido','required|valid_email');
    $this->form_validation->set_rules('password', 'Contraseña', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password2]');
    if($this->form_validation->run()){
      $id = $this->input->post('id',TRUE);
      $password = $this->input->post('password',TRUE);
      $data = array(
        'nombre' => $this->input->post('nombre',TRUE),
        'apellido' => $this->input->post('apellido',TRUE),
        'username' => $this->input->post('username',TRUE),
        'email' => $this->input->post('email',TRUE),
      );
      //si el ususario escribe una contraseña, actualizarla
      if ($password){
        $data['password'] = $password;
      }
      //agrego al usuario a los grupos elegidos
      $this->ion_auth->remove_from_group(NULL, $id);
      foreach ($grupos as $g) {
        $this->ion_auth->add_to_group($g, $id);
      }
      //modifico datos y cargo vista para mostrar resultado
      $res = $this->ion_auth->update($id, $data);
      $data['mensaje'] = ($res)?'La operación se realizó con éxito.':'Se produjo un error al intentar modificar los datos del usuario.';
      $data['link'] = site_url("usuarios/ver/$id"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina del departamento
      $this->ver($this->input->post('id',TRUE));
    }
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
      if($this->form_validation->run()){
        //en caso de que los datos sean correctos, realizo login
        if ($this->ion_auth->login($this->input->post('Usuario'), $this->input->post('Contrasena'), (bool) $this->input->post('Recordarme'))){
          //si el usuario ingresó datos de acceso válidos
          $data['usuarioLogin'] = $this->ion_auth->user()->row();
          $this->load->view('index', $data);
        }
        else{
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
   * Cambiar contraseña
   */
  function cambiarContrasena(){
    if (!$this->ion_auth->logged_in()){
      redirect('/', 'refresh');
    }
    $this->form_validation->set_rules('Contrasena', 'Contraseña anterior:', 'required');
    $this->form_validation->set_rules('NuevaContrasena', 'Nueva contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[NuevaContrasena2]');
    $this->form_validation->set_rules('NuevaContrasena2', 'Confirmar contraseña', 'required');
    if ($this->form_validation->run()){
      $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
      $change = $this->ion_auth->change_password($identity, $this->input->post('Contrasena'), $this->input->post('NuevaContrasena'));
      if ($change){
        $data['mensaje'] = 'La operación se realizó con éxito.';
        $data['link'] = site_url('usuarios');
        $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
        $this->load->view('resultado_operacion', $data);                
      }
      else{
        $data['mensaje'] = 'Ocurrió un error al intentar cambiar la contraseña.';
        $data['link'] = site_url('usuarios/cambiarContrasena');
        $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
        $this->load->view('resultado_operacion', $data);
      }
    }
    else{
      $user = $this->ion_auth->user()->row();        
      $data['user_id'] = $user->id;
      $data['usuarioLogin'] = $user;
      $this->load->view('cambiar_contraseña');
    }
  }

  /*
   * Formulario para iniciar el proceso de cambiar contraseña. 
   */
  function recuperarContrasena(){
    $this->form_validation->set_rules('email', 'E-mail', 'valid_email|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if ($this->form_validation->run()){
      //obtener la identity para el email
      $config_tables = $this->config->item('tables', 'ion_auth');
      $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();
      if ($identity){
        //enviar un email con un codigo de activación 
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
        if ($forgotten){
            $data['mensaje'] = 'Se envió un correo elecrónico con un código de activación para continuar con el proceso.';
            $data['link'] = site_url(''); //pagina principal
        }
        else{
          $data['mensaje'] = 'Ha ocurrido un problema al enviar el código de activación. Por favor intente nuevamente.';
          $data['link'] = site_url('usuarios/recuperarContrasena');          
        }
      }
      else{
        $data['mensaje'] = 'No existe un usuario registrado con el email ingresado.';
        $data['link'] = site_url('usuarios/recuperarContrasena');
      }
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $data['email'] = $this->input->post('email');
      $this->load->view('recuperar_contrasena',$data);
    }
  }

  /*
   * Resetear la contraseña. Es el paso final, donde el usuari recibe el email y confirma que quiere resetear la ontraseña.
   */
  public function resetearContrasena($code = NULL){
    if (!$code){
      show_404();
      return;
    }
    //verificar si el codigo es correcto. Devuelve un objeto, o falso en caso de error
    $user = $this->ion_auth->forgotten_password_check($code);
    //si codigo es correcto
    if ($user){
      $this->form_validation->set_rules('NuevaContrasena', 'Nueva contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[NuevaContrasena]');
      $this->form_validation->set_rules('ConfirmarContrasena', 'Confirmar contraseña', 'required');
      if ($this->form_validation->run()){
        //verifico que la solicitud es valida, comprobando el id de ususario y el codigo aleatorio temporal
        if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')){
          //algo sospechoso pasa. Borro el codigo y muestro mensaje
          $this->ion_auth->clear_forgotten_password_code($code);
          $data['mensaje'] = 'Este formulario no pasó nuestras verificaciones de seguridad. Por favor intente nuevamente.';
          $data['link'] = site_url('usuarios/recuperarContrasena');
        }
        else{
          //si todo sale bien, cambio la contraseña
          $identity = $user->{$this->config->item('identity', 'ion_auth')};
          $change = $this->ion_auth->reset_password($identity, $this->input->post('NuevaContrasena'));
          if ($change){
            $data['mensaje'] = 'La operación se realizó con éxito.';
            $data['link'] = site_url(''); //pagina principal
          }
          else{
            $data['mensaje'] = 'Ocurrió un error al intentar cambiar la contraseña.';
            $data['link'] = site_url(''); //pagina principal
          }
        }
        $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
        $this->load->view('resultado_operacion', $data);
      }
      else{
        //mostrar el formulario de cambio de contraseña
        $data['csrf'] = $this->_get_csrf_nonce();
        $data['code'] = $code;
        $data['user_id'] = $user->id;
        $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
        $this->load->view('cambiar_contrasena',$data);
      }
    }
    else{
      //si el codigo es inválido, enviar a la pagina para empezar el proceso de nuevo
      $data['mensaje'] = 'El código para resetear la contraseña es inválido.';
      $data['link'] = site_url('usuarios/recuperarContrasena'); //pagina principal
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('resultado_operacion', $data);     
    }
  }

  /*
   * Recepción del formulario para activar una cuenta de usuario
   * POST: id
   */
  public function activar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $id = $this->input->post('id', TRUE);
      //activo la cuenta y muestro el resultado
      $res = $this->ion_auth->update($id, array('active'=>1));
      $this->data['mensaje'] = ($res)?'La operación se realizó con éxito.':'No se pudo activar la cuenta de usuario.';
      $this->data['link'] = site_url("usuarios/ver/$id"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->ver($this->input->post('id'));
    }
  }

  /*
   * Recepción del formulario para desactivar una cuenta de usuario
   * POST: id
   */
  function desactivar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $id = $this->input->post('id', TRUE);
      //activo la cuenta y muestro el resultado
      $res = $this->ion_auth->update($id, array('active'=>0));
      $this->data['mensaje'] = ($res)?'La operación se realizó con éxito.':'No se pudo activar la cuenta de usuario.';
      $this->data['link'] = site_url("usuarios/ver/$id"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->ver($this->input->post('id'));
    }
  }

  /*
   * Función para responder solicitudes AJAX
   */
  public function buscarAJAX(){
    $buscar = $this->input->post('buscar');
    //VERIFICAR
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gu');
    $usuarios = $this->gu->buscar($buscar);
    echo "\n";
    foreach ($usuarios as $usuario) {
      echo  "$usuario->id\t".
            "$usuario->nombre\t".
            "$usuario->apellido\t\n";
    }
  }
  
  /*
   * Generar clave aleatoria
   */
  function _get_csrf_nonce(){
    $this->load->helper('string');
    $key   = random_string('alnum', 8);
    $value = random_string('alnum', 20);
    $this->session->set_flashdata('csrfkey', $key);
    $this->session->set_flashdata('csrfvalue', $value);
    return array($key, $value);
  }

  /*
   * Verificar clave aleatoria
   */
  function _valid_csrf_nonce(){
    if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
        $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')){
      return TRUE;
    }
    else{
      return FALSE;
    }
  }
}
