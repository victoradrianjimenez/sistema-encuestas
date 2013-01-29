<?php

/**
 * 
 */
class Usuarios extends CI_Controller{
  
    function __construct()
  {
    parent::__construct();
    $this->load->library('ion_auth');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->helper('url');

    $this->load->database();

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
  }
  
  
  //redirect if needed, otherwise display the user list
  function index()
  {

    if (!$this->ion_auth->logged_in())
    {

      //redirect them to the login page
      redirect('claves/ingresar', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      //redirect them to the home page because they must be an administrator to view this
      redirect('/', 'refresh');
    }
    else
    {
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

      //list the users
      $this->data['users'] = $this->ion_auth->users()->result();
      foreach ($this->data['users'] as $k => $user)
      {
        $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
      }

      $this->_render_page('auth/index', $this->data);
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
  function cambiarContraseña()
  {
    $this->form_validation->set_rules('Contrasena', 'Contraseña anterior:', 'required');
    $this->form_validation->set_rules('NuevaContrasena', 'Nueva contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
    $this->form_validation->set_rules('NuevaContrasena2', 'Confirmar contraseña', 'required');

    if (!$this->ion_auth->logged_in())
    {
      redirect('auth/login', 'refresh');
    }

    $user = $this->ion_auth->user()->row();

    if ($this->form_validation->run() == false)
    {
      /*
$this->load->view('index', $data);            
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
        */              
                    
                  
                
              
            
          
        
      
      //display the form
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

      $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
      $this->data['old_password'] = array(
        'name' => 'old',
        'id'   => 'old',
        'type' => 'password',
      );
      $this->data['new_password'] = array(
        'name' => 'new',
        'id'   => 'new',
        'type' => 'password',
        'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
      );
      $this->data['new_password_confirm'] = array(
        'name' => 'new_confirm',
        'id'   => 'new_confirm',
        'type' => 'password',
        'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
      );
      $this->data['user_id'] = array(
        'name'  => 'user_id',
        'id'    => 'user_id',
        'type'  => 'hidden',
        'value' => $user->id,
      );

      //render
      $this->_render_page('auth/change_password', $this->data);
    }
    else
    {
      $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

      $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

      if ($change)
      {
        //if the password was successfully changed
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        $this->logout();
      }
      else
      {
        $this->session->set_flashdata('message', $this->ion_auth->errors());
        redirect('auth/change_password', 'refresh');
      }
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


  //activate the user
  function activate($id, $code=false)
  {
    if ($code !== false)
    {
      $activation = $this->ion_auth->activate($id, $code);
    }
    else if ($this->ion_auth->is_admin())
    {
      $activation = $this->ion_auth->activate($id);
    }

    if ($activation)
    {
      //redirect them to the auth page
      $this->session->set_flashdata('message', $this->ion_auth->messages());
      redirect("auth", 'refresh');
    }
    else
    {
      //redirect them to the forgot password page
      $this->session->set_flashdata('message', $this->ion_auth->errors());
      redirect("auth/forgot_password", 'refresh');
    }
  }

  //deactivate the user
  function deactivate($id = NULL)
  {
    $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

    $this->load->library('form_validation');
    $this->form_validation->set_rules('confirm', 'confirmation', 'required');
    $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

    if ($this->form_validation->run() == FALSE)
    {
      // insert csrf check
      $this->data['csrf'] = $this->_get_csrf_nonce();
      $this->data['user'] = $this->ion_auth->user($id)->row();

      $this->_render_page('auth/deactivate_user', $this->data);
    }
    else
    {
      // do we really want to deactivate?
      if ($this->input->post('confirm') == 'yes')
      {
        // do we have a valid request?
        if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
        {
          show_error('This form post did not pass our security checks.');
        }

        // do we have the right userlevel?
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
        {
          $this->ion_auth->deactivate($id);
        }
      }

      //redirect them back to the auth page
      redirect('auth', 'refresh');
    }
  }

  //create a new user
  function create_user()
  {
    $this->data['title'] = "Create User";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
      redirect('auth', 'refresh');
    }

    //validate form input
    $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
    $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
    $this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
    $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

    if ($this->form_validation->run() == true)
    {
      $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
      $email    = $this->input->post('email');
      $password = $this->input->post('password');

      $additional_data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'company'    => $this->input->post('company'),
        'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
      );
    }
    if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
    {
      //check to see if we are creating the user
      //redirect them back to the admin page
      $this->session->set_flashdata('message', $this->ion_auth->messages());
      redirect("auth", 'refresh');
    }
    else
    {
      //display the create user form
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

      $this->data['first_name'] = array(
        'name'  => 'first_name',
        'id'    => 'first_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('first_name'),
      );
      $this->data['last_name'] = array(
        'name'  => 'last_name',
        'id'    => 'last_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('last_name'),
      );
      $this->data['email'] = array(
        'name'  => 'email',
        'id'    => 'email',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('email'),
      );
      $this->data['company'] = array(
        'name'  => 'company',
        'id'    => 'company',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('company'),
      );
      $this->data['phone1'] = array(
        'name'  => 'phone1',
        'id'    => 'phone1',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone1'),
      );
      $this->data['phone2'] = array(
        'name'  => 'phone2',
        'id'    => 'phone2',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone2'),
      );
      $this->data['phone3'] = array(
        'name'  => 'phone3',
        'id'    => 'phone3',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone3'),
      );
      $this->data['password'] = array(
        'name'  => 'password',
        'id'    => 'password',
        'type'  => 'password',
        'value' => $this->form_validation->set_value('password'),
      );
      $this->data['password_confirm'] = array(
        'name'  => 'password_confirm',
        'id'    => 'password_confirm',
        'type'  => 'password',
        'value' => $this->form_validation->set_value('password_confirm'),
      );

      $this->_render_page('auth/create_user', $this->data);
    }
  }

  //edit a user
  function edit_user($id)
  {
    $this->data['title'] = "Edit User";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
      redirect('auth', 'refresh');
    }

    $user = $this->ion_auth->user($id)->row();
    $groups=$this->ion_auth->groups()->result_array();
    $currentGroups = $this->ion_auth->get_users_groups($id)->result();

    //process the phone number
    if (isset($user->phone) && !empty($user->phone))
    {
      $user->phone = explode('-', $user->phone);
    }

    //validate form input
    $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
    $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
    $this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
    $this->form_validation->set_rules('groups', 'Groups', 'xss_clean');

    if (isset($_POST) && !empty($_POST))
    {
      // do we have a valid request?
      if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
      {
        show_error('This form post did not pass our security checks.');
      }

      $data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'company'    => $this->input->post('company'),
        'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
      );

      //Update the groups user belongs to
      $groupData = $this->input->post('groups');

      if (isset($groupData) && !empty($groupData)) {

        $this->ion_auth->remove_from_group('', $id);

        foreach ($groupData as $grp) {
          $this->ion_auth->add_to_group($grp, $id);
        }

      }

      //update the password if it was posted
      if ($this->input->post('password'))
      {
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        $data['password'] = $this->input->post('password');
      }

      if ($this->form_validation->run() === TRUE)
      {
        $this->ion_auth->update($user->id, $data);

        //check to see if we are creating the user
        //redirect them back to the admin page
        $this->session->set_flashdata('message', "User Saved");
        redirect("auth", 'refresh');
      }
    }

    //display the edit user form
    $this->data['csrf'] = $this->_get_csrf_nonce();

    //set the flash data error message if there is one
    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

    //pass the user to the view
    $this->data['user'] = $user;
    $this->data['groups'] = $groups;
    $this->data['currentGroups'] = $currentGroups;

    $this->data['first_name'] = array(
      'name'  => 'first_name',
      'id'    => 'first_name',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('first_name', $user->first_name),
    );
    $this->data['last_name'] = array(
      'name'  => 'last_name',
      'id'    => 'last_name',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('last_name', $user->last_name),
    );
    $this->data['company'] = array(
      'name'  => 'company',
      'id'    => 'company',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('company', $user->company),
    );
    $this->data['phone1'] = array(
      'name'  => 'phone1',
      'id'    => 'phone1',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
    );
    $this->data['phone2'] = array(
      'name'  => 'phone2',
      'id'    => 'phone2',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone2', $user->phone[1]),
    );
    $this->data['phone3'] = array(
      'name'  => 'phone3',
      'id'    => 'phone3',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone3', $user->phone[2]),
    );
    $this->data['password'] = array(
      'name' => 'password',
      'id'   => 'password',
      'type' => 'password'
    );
    $this->data['password_confirm'] = array(
      'name' => 'password_confirm',
      'id'   => 'password_confirm',
      'type' => 'password'
    );

    $this->_render_page('auth/edit_user', $this->data);
  }

  // create a new group
  function create_group()
  {
    $this->data['title'] = "Create Group";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
      redirect('auth', 'refresh');
    }

    //validate form input
    $this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
    $this->form_validation->set_rules('description', 'Description', 'xss_clean');

    if ($this->form_validation->run() == TRUE)
    {
      $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
      if($new_group_id)
      {
        // check to see if we are creating the group
        // redirect them back to the admin page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect("auth", 'refresh');
      }
    }
    else
    {
      //display the create group form
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

      $this->data['group_name'] = array(
        'name'  => 'group_name',
        'id'    => 'group_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('group_name'),
      );
      $this->data['description'] = array(
        'name'  => 'description',
        'id'    => 'description',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('description'),
      );

      $this->_render_page('auth/create_group', $this->data);
    }
  }

  //edit a group
  function edit_group($id)
  {
    // bail if no group id given
    if(!$id || empty($id))
    {
      redirect('auth', 'refresh');
    }

    $this->data['title'] = "Edit Group";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
    {
      redirect('auth', 'refresh');
    }

    $group = $this->ion_auth->group($id)->row();

    //validate form input
    $this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
    $this->form_validation->set_rules('group_description', 'Group Description', 'xss_clean');

    if (isset($_POST) && !empty($_POST))
    {
      if ($this->form_validation->run() === TRUE)
      {
        $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

        if($group_update)
        {
          $this->session->set_flashdata('message', "Group Saved");
        }
        else
        {
          $this->session->set_flashdata('message', $this->ion_auth->errors());
        }
        redirect("auth", 'refresh');
      }
    }

    //set the flash data error message if there is one
    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

    //pass the user to the view
    $this->data['group'] = $group;

    $this->data['group_name'] = array(
      'name'  => 'group_name',
      'id'    => 'group_name',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('group_name', $group->name),
    );
    $this->data['group_description'] = array(
      'name'  => 'group_description',
      'id'    => 'group_description',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('group_description', $group->description),
    );

    $this->_render_page('auth/edit_group', $this->data);
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
