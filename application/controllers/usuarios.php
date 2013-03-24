<?php

/**
 * Controlador para la gestión de usuarios
 */
class Usuarios extends CI_Controller{
  
  var $data = array(); //datos para mandar a las vistas

  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    //leo los datos del usuario logueado
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    //leo los mensajes generados en la página anterior
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de usuarios.
   */
  public function listarDecanos($pagInicio=0){
    $this->_listarGrupo($pagInicio,'decanos',site_url("usuarios/listarDecanos"));
  }
  public function listarJefesDepartamentos($pagInicio=0){
    $this->_listarGrupo($pagInicio,'jefes_departamentos',site_url("usuarios/listarJefesDepartamentos"));
  }
  public function listarDirectores($pagInicio=0){
    $this->_listarGrupo($pagInicio,'directores',site_url("usuarios/listarDirectores"));
  }
  public function listarDocentes($pagInicio=0){
    $this->_listarGrupo($pagInicio,'docentes',site_url("usuarios/listarDocentes"));
  }
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('/');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $this->load->model('Usuario');
    $this->load->model('Gestor_usuarios','gu');
    $this->_listar($this->gu->listar($pagInicio, PER_PAGE), $this->gu->cantidad(), site_url("usuarios/listar"));
  }
  private function _listarGrupo($pagInicio, $grupo, $url){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('/');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $this->load->model('Usuario');
    $this->load->model('Gestor_usuarios','gu');
    $grupos = $this->ion_auth->groups()->result();
    foreach ($grupos as $g) {
      if($g->name == $grupo){
        $this->data['grupo'] = $g;
        $this->_listar($this->gu->listarGrupo($g->id, $pagInicio, PER_PAGE), $this->gu->cantidadGrupo($g->id), $url);
      }
    }
  }
  private function _listar($usuarios, $cantidad, $url){
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');

    $lista = array(); //datos para mandar a la vista
    foreach ($usuarios as $i => $usuario) {
      $lista[$i] = array(
        'usuario' => $usuario,
        'grupos' => $this->ion_auth->get_users_groups($usuario->id)->result()
      );
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => $url,
      'total_rows' => $cantidad
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Usuarios
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_usuarios', $this->data);
  }


  /*
   * Recepción del formulario para agregar nuevo departamento
   * POST: apellido, nombre, username, password, password2, email, grupos, active
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/listar');
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Usuario');
    $this->load->model('Gestor_imagenes', 'gi');
    
    //leo los datos POST
    $this->Usuario->id = null;
    $this->Usuario->apellido = $this->input->post('apellido',TRUE);
    $this->Usuario->nombre = $this->input->post('nombre',TRUE);
    $this->Usuario->username = $this->input->post('username',TRUE);
    $this->Usuario->email = $this->input->post('email',TRUE);
    $this->Usuario->active = (isset($_POST['active'])) ? $this->input->post('active') : 1;
    $noImagen = ((bool)$this->input->post('noImagen')); //si es verdadero, se debe eliminar foto del usuario
    $grupos = ($this->input->post('grupos'))? $this->input->post('grupos') : array(); //leo los grupos a los que pertenece el nuevo usuario
    
    //verifico datos POST
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|max_length[40]|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[40]');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_dash_space|max_length[100]');
    $this->form_validation->set_rules('email','E-mail','required|valid_email|max_length[100]');
    $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password2]');
    if($this->form_validation->run()){
      $error = false;
      $idImagen = null;
      //si el usuario sube una imagen, guardarla
      if (isset($_FILES['imagen']) && !$noImagen){
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
          $idImagen = $this->gi->alta($_FILES['imagen']['tmp_name'], $_FILES['imagen']['type']);
          //si la imagen no pudo guardarse en la base de datos
          if (!is_numeric($idImagen)){
            $error = true;
            $this->session->set_flashdata('resultadoOperacion', $idImagen);
            $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          }
        }
      }
      //si no hubo error, modifico datos del usuario
      if (!$error){
        $additional_data = array(
          'apellido' => $this->Usuario->apellido,
          'nombre' => $this->Usuario->nombre,
          'active' => $this->Usuario->active,
          'idImagen' => $idImagen
        );
        $res = $this->ion_auth->register($this->Usuario->username, $this->input->post('password'), $this->Usuario->email, $additional_data, $grupos);
        if (is_numeric($res)){
          $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
          $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
          redirect('usuarios/listar');
        }
        $this->data['resultadoOperacion'] = $res;
        $this->data['resultadoTipo'] = ALERT_ERROR;
      }
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    $this->data['usuario'] = $this->Usuario;
    $this->data['usuario_grupos'] = &$grupos;
    $this->data['grupos'] = $this->ion_auth->groups()->result();
    $this->data['noImagen'] = $noImagen;
    $this->data['tituloFormulario'] = 'Nuevo Usuario';
    $this->data['urlFormulario'] = site_url('usuarios/nuevo');
    $this->load->view('editar_usuario', $this->data);
  }


  /*
   * Recepción del formulario para modificar los datos de una Usuario
   * POST: id, apellido, nombre, username, password, password2, email, grupos
   */
  public function modificar($id=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/listar');
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Gestor_usuarios', 'gu');
    $this->load->model('Gestor_imagenes', 'gi');
    
    //leo los datos POST
    $this->Usuario->id = (int)$this->input->post('id');
    $this->Usuario->apellido = $this->input->post('apellido',TRUE);
    $this->Usuario->nombre = $this->input->post('nombre',TRUE);
    $this->Usuario->username = $this->input->post('username',TRUE);
    $this->Usuario->email = $this->input->post('email',TRUE);
    $this->Usuario->active = (isset($_POST['active'])) ? $this->input->post('active') : 1;
    $noImagen = ((bool)$this->input->post('noImagen')); //si es verdadero, se debe eliminar foto del usuario
    $grupos = ($this->input->post('grupos'))? $this->input->post('grupos') : array(); //leo los grupos a los que pertenece el nuevo usuario
    
    //verifico datos POST
    $this->form_validation->set_rules('id','Identificador','is_natural_no_zero|required');
    $this->form_validation->set_rules('apellido','Apellido','alpha_dash_space|max_length[40]|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[40]');
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_dash_space|max_length[100]');
    $this->form_validation->set_rules('email','E-mail','required|valid_email|max_length[100]');
    $this->form_validation->set_rules('password', 'Contraseña', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password2]');
    if($this->form_validation->run()){
      $error = false;
      $idImagen = null;
      //si el usuario sube una imagen, guardarla
      if (isset($_FILES['imagen']) && !$noImagen){
        //verificar si la imagen se subio bien
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
          $idImagen = $this->gi->alta($_FILES['imagen']['tmp_name'], $_FILES['imagen']['type']);
          if (!is_numeric($idImagen)){
            $error = true;
            $this->data['resultadoOperacion'] = $idImagen;
            $this->data['resultadoTipo'] = ALERT_ERROR;
          }
        }
      }
      //si no hubo error, modifico datos del usuario
      if (!$error){
        $data = array(
          'nombre' => $this->input->post('nombre',TRUE),
          'apellido' => $this->input->post('apellido',TRUE),
          'username' => $this->input->post('username',TRUE),
          'email' => $this->input->post('email',TRUE),
          'active' => $this->Usuario->active
        );
        //si el ususario sube una imagen, actualizarla
        if ($idImagen){
          $data['idImagen'] = $idImagen;
        }
        if ($noImagen){
          $data['idImagen'] = NULL;
        }
        //si el ususario escribe una contraseña, actualizarla
        if ($password){
          $data['password'] = $this->input->post('password');
        }
        //agrego al usuario a los grupos elegidos
        $this->ion_auth->remove_from_group(NULL, $this->Usuario->id);
        foreach ($grupos as $g) {
          $this->ion_auth->add_to_group($g, $this->Usuario->id);
        }
        //modifico datos y cargo vista para mostrar resultado
        $usuarioAnterior = $this->gu->dame($id);
        $res = $this->ion_auth->update($this->Usuario->id, $data);
        //si los datos del usuario se guardaron con exito
        if ($res){
          //si el usuario no quiere una imagen o subió una nueva, elimino la imagen anterior
          if ($noImagen || $idImagen) $this->gi->baja($usuarioAnterior->idImagen);
          $this->session->set_flashdata('resultadoOperacion', 'La modificación de los datos del usuario se realizó con éxito.');
          $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
          redirect('usuarios/listar');
        }
        else{
          if (!$idImagen) $this->gi->baja($idImagen); //como hubo error, borro la imagen que se acaba de dar de alta
          $this->data['resultadoOperacion'] = 'Se produjo un error al intentar modificar los datos del usuario.';
          $this->data['resultadoTipo'] = ALERT_ERROR;
        }
      }
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if (!$id) redirect('usuarios/nuevo');
    $this->Usuario = $this->gu->dame((int)$id);
    if (!$this->Usuario){
      $this->session->set_flashdata('resultadoOperacion', "No existe el usuario seleccionado.");
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      redirect('usuarios/listar');
    }
    $this->data['usuario'] = $this->Usuario;
    $this->data['usuario_grupos'] = $this->ion_auth->get_users_groups($id)->result();
    $this->data['grupos'] = $this->ion_auth->groups()->result();
    $this->data['noImagen'] = $noImagen;
    $this->data['tituloFormulario'] = 'Modificar Usuario';
    $this->data['urlFormulario'] = site_url('usuarios/modificar');
    $this->load->view('editar_usuario', $this->data);
  }


  /*
   * Recepción del formulario para modificar los datos de una cuenta Usuario (cuando el mismo usuario lo hace)
   * POST: username, password, email
   */
  public function modificarCuenta(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Gestor_imagenes', 'gi');
    
    //leo datos POST
    $this->Usuario->username = $this->input->post('username',TRUE);
    $this->Usuario->email = $this->input->post('email',TRUE);
    $noImagen = ((bool)$this->input->post('noImagen')); //si es verdadero, se debe eliminar foto del usuario
    
    //verifico datos POST
    $this->form_validation->set_rules('username','Nombre de usuario','required|alpha_dash_space|max_length[100]');
    $this->form_validation->set_rules('email','E-mail','required|valid_email|max_length[100]');
    $this->form_validation->set_rules('password', 'Contraseña', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password2]');
    if($this->form_validation->run()){
      $error = false;
      $idImagen = null;
      //si el usuario sube una imagen, guardarla
      if (isset($_FILES['imagen']) && !$noImagen){
        //verificar si la imagen se subio bien
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
          $idImagen = $this->gi->alta($_FILES['imagen']['tmp_name'], $_FILES['imagen']['type']);
          if (!is_numeric($idImagen)){
            $error = true;
            $this->data['resultadoOperacion'] = $idImagen;
            $this->data['resultadoTipo'] = ALERT_ERROR;
          }
        }
      }
      //si no hubo error, modifico datos del usuario
      if (!$error){
        $data = array(
          'username' => $this->input->post('username',TRUE),
          'email' => $this->input->post('email',TRUE),
        );
        //si el ususario escribe una contraseña, actualizarla
        if ($idImagen){
          $data['idImagen'] = $idImagen;
        }
        if ($noImagen){
          $data['idImagen'] = NULL;
        }
        //si el ususario escribe una contraseña, actualizarla
        if ($password){
          $data['password'] = $this->input->post('password');
        }
        //modifico datos y cargo vista para mostrar resultado
        $res = $this->ion_auth->update((int)$this->data['usuarioLogin']->id, $data);
        //si los datos del usuario se guardaron con exito
        if ($res){
          //si el usuario no quiere una imagen o subió una nueva, elimino de la base de datos la imagen anterior que tenia el usuario
          if ($noImagen || $idImagen) $this->gi->baja($this->data['usuarioLogin']->idImagen);
          $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
          $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
          redirect('/');
        }
        else{
          if ($idImagen != null) $this->gi->baja($idImagen); //como hubo error, borro la imagen que se acaba de dar de alta
          $this->data['resultadoOperacion'] = 'Se produjo un error al intentar modificar los datos del usuario.';
          $this->data['resultadoTipo'] = ALERT_ERROR;
        }
      }
    }
    $this->data['usuario'] = $this->Usuario;
    $this->data['noImagen'] = $noImagen;
    $this->load->view('editar_cuenta', $this->data);
  }


  /*
   * Recepción del formulario para eliminar un usuario
   * POST: id
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('id','Usuario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_usuarios','gu');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gu->baja((int)$this->input->post('id'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'El usuario se eliminó exitosamente.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('usuarios/listar');
  }

  /*
   * Recibir formulario de inicio de sesión.
   * POST: usuario, contrasena, recordarme
   */
  function login(){
    //verifico si los datos son correctos
    $this->form_validation->set_rules('usuario','Nombre de usuario','required|alpha_dash_space|max_length[100]');      
    $this->form_validation->set_rules('contrasena','Contraseña','min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
    if($this->form_validation->run()){      
      //en caso de que los datos sean correctos, realizo login
      if ($this->ion_auth->login($this->input->post('usuario'), $this->input->post('contrasena'), (bool)$this->input->post('recordarme'))){
        //si el usuario ingresó datos de acceso válidos
        $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
        redirect('/');
      }
      else{
        //si no logró validar
        $this->data['mensajeLogin'] = 'Nombre de usuario y/o contraseña incorrectos, por favor vuelva a intentar.';
        $this->data['showLogin'] = true;
        $this->load->view('ingreso_clave', $this->data);
      }
    }
    else{
      //en caso de que los datos sean incorrectos
      $this->data['showLogin'] = true;
      $this->load->view('ingreso_clave', $this->data);
    }
  }

  /*
   * Cerrar sesión.
   */
  function logout(){
    $logout = $this->ion_auth->logout();
    $this->data['usuarioLogin'] = false;
    $this->load->view('ingreso_clave', $this->data);
  }
  
  /*
   * Formulario para iniciar el proceso de cambiar contraseña. 
   * POST: email, captcha
   */
  function recuperarContrasena(){
    $this->form_validation->set_rules('captcha', 'Código de Verificación', 'callback_validar_captcha');
    $this->form_validation->set_rules('email', 'E-mail', 'valid_email|required');
    if ($this->form_validation->run()){
      //obtener la identity para el email
      $config_tables = $this->config->item('tables', 'ion_auth');
      $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();
      if ($identity){
        //enviar un email con un codigo de activación
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
        if ($forgotten){
          $this->session->set_flashdata('resultadoOperacion', 'Se envió un correo elecrónico con un código de activación para continuar con el proceso.');
          $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
          redirect('/');
        }
        else{
          $this->session->set_flashdata('resultadoOperacion', 'Ha ocurrido un problema al enviar el código de activación. Por favor intente nuevamente.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);        
        }
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', 'No existe un usuario registrado con el email ingresado.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    $this->data['captcha'] = $this->_crear_captcha(200, 40);
    $this->load->view('recuperar_contrasena', $this->data);
  }

  /*
   * Resetear la contraseña. Es el paso final, donde el usuario recibe el email y confirma que quiere resetear la ontraseña.
   * POST: nuevaContrasena, confirmarContrasena
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
          $this->session->set_flashdata('resultadoOperacion', 'Este formulario no pasó nuestras verificaciones de seguridad. Por favor intente nuevamente.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        }
        else{
          //si todo sale bien, cambio la contraseña
          $identity = $user->{$this->config->item('identity', 'ion_auth')};
          $change = $this->ion_auth->reset_password($identity, $this->input->post('nuevaContrasena'));
          if ($change){
            $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
            $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
            redirect('usuarios/login');
          }
          else{
            $this->session->set_flashdata('resultadoOperacion', 'Ocurrió un error al intentar cambiar la contraseña.');
            $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          }
        }
        redirect('usuarios/recuperarContrasena');
      }
      //mostrar el formulario de cambio de contraseña
      $this->data['csrf'] = $this->_get_csrf_nonce();
      $this->data['code'] = $code;
      $this->data['user_id'] = $user->id;
      $this->load->view('cambiar_contrasena',$this->data);
    }
    else{
      $this->session->set_flashdata('resultadoOperacion', 'El código para resetear la contraseña es inválido.');
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      redirect('/');
    }
  }

  /*
   * Función para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar');
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
  }
  
  /*
   * Obtener la imagen (foto) de un usuario, a partir del id de la imagen
   */
  public function imagen($idImagen=null){
    if (!$idImagen){
      $this->load->model('Gestor_imagenes', 'gi');
      $imagen = $this->gi->dame($idImagen);
      if ($imagen){
        ob_end_clean();
        header ('Content-type: '.$imagen->tipo);
        echo base64_decode($imagen->imagen);
        ob_end_clean();
      }
      else
        //cargo imagen por defecto
        redirect(base_url('img/usuario.png'));
    }
    else
      //cargo imagen por defecto
      redirect(base_url('img/usuario.png')); 
  }
  
  /*
   * Verificar captcha (basado en el código de CodeIgniter)
   */
  function validar_captcha($texto=null){
    if ($texto){
      // First, delete old captchas
      $expiration = time() - $this->config->config['captchaExpiration']; // Two hour limit
      $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
      // Then see if a captcha exists:
      $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
      $binds = array($texto, $this->input->ip_address(), $expiration);
      $query = $this->db->query($sql, $binds);
      $row = $query->row();
      if ($row->count > 0){
        return TRUE;
      }
    }
    $this->form_validation->set_message('validar_captcha', 'El campo %s no coincide con el de la imágen.');
    return FALSE;
  }
  
  /*
   * Generar clave aleatoria
   */
  private function _get_csrf_nonce(){
    $this->load->helper('string');
    $key = random_string('alnum', 8);
    $value = random_string('alnum', 20);
    $this->session->set_flashdata('csrfkey', $key);
    $this->session->set_flashdata('csrfvalue', $value);
    return array($key, $value);
  }

  /*
   * Verificar clave aleatoria
   */
  private function _valid_csrf_nonce(){
    if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
        $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')){
      return TRUE;
    }
    return FALSE;
  }
  
  /*
   * Crear imagen de captcha
   */
  private function _crear_captcha($ancho, $alto){
    $this->load->helper('captcha');
    $vals = array(
      'img_path'   => './captcha/',
      'img_url'  => base_url('captcha').'/',
      'font_path'  => $this->config->config['captchaFont'],
      'img_width'  => $ancho,
      'img_height' => $alto,
      'expiration' => $this->config->config['captchaExpiration']
    );
    $cap = create_captcha($vals);
    $data = array(
      'captcha_time'  => $cap['time'], 
      'ip_address'  => $this->input->ip_address(), 
      'word' => $cap['word']);
    $query = $this->db->insert_string('captcha', $data);
    $this->db->query($query);
    return $cap['image'];
  }
}
