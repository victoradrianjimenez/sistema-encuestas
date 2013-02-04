<?php

/**
 * 
 */
class Usuarios extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de usuarios.
   * Última revisión: 2012-02-01 3:35 p.m.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Gestor_usuarios','gu');

    //obtengo lista de usuarios
    $usuarios = $this->gu->listar($pagInicio, self::per_page);
    $lista = array(); //datos para mandar a la vista
    foreach ($usuarios as $i => $usuario) {
      $lista[$i] = array(
        'usuario' => $usuario,
        'grupos' => $this->ion_auth->get_users_groups($usuario->id)->result()
      );
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("usuarios/listar"),
      'total_rows' => $this->gu->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Usuarios
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->data['usuario'] = &$this->Usuario; //datos por defecto de un nuevo usuario
    $this->data['usuario_grupos'] = array(); //datos por defecto de un nuevo usuario
    $this->data['grupos'] = $this->ion_auth->groups()->result();
    $this->load->view('lista_usuarios', $this->data);
  }
  
  /*
   * Ver y editar datos relacionados a una Usuario
   * Última revisión: 2012-02-01 5:47 p.m.
   */
  public function ver($id=null, $pagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $id = (int)$id;
    
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Gestor_Usuarios','gu');
    
    //obtengo datos del usuario 
    $usuario =  $this->gu->dame($id);
    if ($usuario){
      $this->data['usuario'] = $usuario;
      $this->data['usuario_grupos'] = $this->ion_auth->get_users_groups($id)->result();        
      $this->data['grupos'] = $this->ion_auth->groups()->result();
      $this->load->view('ver_usuario', $this->data);
    }
    else{
      show_error('El Identificador de usuario no es válido.');
    }
  }
  
  /*
   * Recepción del formulario para agregar nuevo departamento
   * POST: apellido, nombre, username, password, email
   * Última revisión: 2012-02-01 6:08 p.m.
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
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
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|max_length[40]|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[40]');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_dash_space|max_length[100]');
    $this->form_validation->set_rules('email','E-mail','required|valid_email');
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
   * Última revisión: 2012-02-01 6:10 p.m.
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
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
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|max_length[40]|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[40]');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_dash_space|max_length[100]');
    $this->form_validation->set_rules('email','E-mail','required|valid_email');
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
   * Recepción del formulario para eliminar un usuario
   * POST: idMateria
   * Última revisión: 2012-02-01 3:44 p.m.
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_usuarios','gu');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gu->baja($this->input->post('id',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("usuarios/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Recibir formulario de inicio de sesión.
   * Última revisión: 2012-02-01 6:18 p.m.
   */
  function login(){
    //verifico si los datos son correctos
    $this->form_validation->set_rules('usuario','Nombre de usuario','required|alpha_dash_space|max_length[100]');      
    $this->form_validation->set_rules('contrasena','Contraseña','min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
    if($this->form_validation->run()){      
      //en caso de que los datos sean correctos, realizo login
      if ($this->ion_auth->login($this->input->post('usuario'), $this->input->post('contrasena'), (bool) $this->input->post('recordarme'))){
        //si el usuario ingresó datos de acceso válidos
        $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
        $this->load->view('index', $this->data);
      }
      else{
        //si no logró validar
        $this->data['mensajeLogin']="Nombre de usuario y/o contraseña incorrectos, por favor vuelva a intentar.";
        $this->load->view('index', $this->data);
      }
    }
    else{
      //en caso de que los datos sean incorrectos
      $this->load->view('index', $this->data);
    }
  }

  /*
   * Cerrar sesión.
   * Última revisión: 2012-02-01 6:39 p.m.
   */
  function logout(){
    $logout = $this->ion_auth->logout();
    $this->data['usuarioLogin'] = NULL;
    $this->load->view('index', $this->data);
  }

  /*
   * Cambiar contraseña una vez logueado
   * Última revisión: 2012-02-01 7:50 p.m.
   */
  function cambiarContrasena(){
    if (!$this->ion_auth->logged_in()){redirect('/', 'refresh');}
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
   * Última revisión: 2012-02-01 6:59 p.m.
   */
  function recuperarContrasena(){
    $this->form_validation->set_rules('email', 'E-mail', 'valid_email|required');
    if ($this->form_validation->run()){
      //obtener la identity para el email
      $config_tables = $this->config->item('tables', 'ion_auth');
      $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();
      if ($identity){
        //enviar un email con un codigo de activación
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
        if ($forgotten){
          $this->data['mensaje'] = 'Se envió un correo elecrónico con un código de activación para continuar con el proceso.';
          $this->data['link'] = site_url(''); //pagina principal
        }
        else{
          $this->data['mensaje'] = 'Ha ocurrido un problema al enviar el código de activación. Por favor intente nuevamente.';
          $this->data['link'] = site_url('usuarios/recuperarContrasena');          
        }
      }
      else{
        $this->data['mensaje'] = 'No existe un usuario registrado con el email ingresado.';
        $this->data['link'] = site_url('usuarios/recuperarContrasena');
      }
      $this->load->view('resultado_operacion', $this->data);      
    }
    else{
      $this->load->view('recuperar_contrasena',$this->data);
    }
  }

  /*
   * Resetear la contraseña. Es el paso final, donde el usuario recibe el email y confirma que quiere resetear la ontraseña.
   * Última revisión: 2012-02-01 7:59 p.m.
   */
  public function resetearContrasena($code = NULL){
    if (!$code){show_404(); return;}
    //verificar si el codigo es correcto. Devuelve un objeto, o falso en caso de error
    $user = $this->ion_auth->forgotten_password_check($code);
    //si codigo es correcto
    if ($user){
      $this->form_validation->set_rules('nuevaContrasena', 'Nueva contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[confirmarContrasena]');
      $this->form_validation->set_rules('confirmarContrasena', 'Confirmar contraseña', 'required');
      if ($this->form_validation->run()){
        //verifico que la solicitud es valida, comprobando el id de ususario y el codigo aleatorio temporal
        if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')){
          //algo sospechoso pasa. Borro el codigo y muestro mensaje
          $this->ion_auth->clear_forgotten_password_code($code);
          $this->data['mensaje'] = 'Este formulario no pasó nuestras verificaciones de seguridad. Por favor intente nuevamente.';
          $this->data['link'] = site_url('usuarios/recuperarContrasena');
        }
        else{
          //si todo sale bien, cambio la contraseña
          $identity = $user->{$this->config->item('identity', 'ion_auth')};
          $change = $this->ion_auth->reset_password($identity, $this->input->post('nuevaContrasena'));
          if ($change){
            $this->data['mensaje'] = 'La operación se realizó con éxito.';
            $this->data['link'] = site_url(''); //pagina principal
          }
          else{
            $this->data['mensaje'] = 'Ocurrió un error al intentar cambiar la contraseña.';
            $this->data['link'] = site_url(''); //pagina principal
          }
        }
        $this->load->view('resultado_operacion', $this->data);
      }
      else{
        //mostrar el formulario de cambio de contraseña
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['code'] = $code;
        $this->data['user_id'] = $user->id;
        echo 'dfdf';
        $this->load->view('cambiar_contrasena',$this->data);
      }
    }
    else{
      //si el codigo es inválido, enviar a la pagina para empezar el proceso de nuevo
      $this->data['mensaje'] = 'El código para resetear la contraseña es inválido.';
      $this->data['link'] = site_url('usuarios/recuperarContrasena'); //pagina principal
      $this->load->view('resultado_operacion', $this->data);     
    }
  }

  /*
   * Recepción del formulario para activar una cuenta de usuario
   * POST: id
   * Última revisión: 2012-02-02 2:31 a.m.
   */
  public function activar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
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
   * Última revisión: 2012-02-02 2:31 a.m.
   */
  function desactivar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $id = $this->input->post('id', TRUE);
      //activo la cuenta y muestro el resultado
      if(!$this->ion_auth->is_admin($id)){
        $res = $this->ion_auth->update($id, array('active'=>0));
        $this->data['mensaje'] = ($res)?'La operación se realizó con éxito.':'No se pudo activar la cuenta de usuario.';
      }
      else{
        $this->data['mensaje'] = 'No se puede desactivar una cuenta perteneciente al grupo de Administradores.';
      }
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
   * Última revisión: 2012-02-02 2:35 a.m.
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
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
   * Última revisión: 2012-02-02 2:35 a.m.
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
   * Última revisión: 2012-02-02 2:35 a.m.
   */
  function _valid_csrf_nonce(){
    if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
        $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')){
      return TRUE;
    }
    return FALSE;
  }
}
