<?php

/**
 * Controlador para la gestión de carreras
 */
class Carreras extends CI_Controller{
  
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
    //por defecto, muestro el listado de departamentos
    $this->listar();
  }

  /*
   * Muestra el listado de carreras.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos
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

    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');
        
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;

    //obtengo lista de carreras
    $carreras = $this->gc->listar($pagInicio, PER_PAGE);
    $lista = array();
    foreach ($carreras as $i => $carrera) {
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $director = $this->gu->dame($carrera->idDirectorCarrera);
      $lista[$i] = array(
        'carrera' => $carrera,
        'departamento' => ($departamento)?$departamento:$this->Departamento,
        'director' => ($director)?$director:$this->Usuario
      );      
    }

    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url('carreras/listar'),
      'total_rows' => $this->gc->cantidad(),
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las Carreras
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_carreras', $this->data);
  }

  /*
   * Ver y editar datos relacionados a una carrera
   */
  public function ver($idCarrera=null, $pagInicio=0){
    //verifico si el usuario tiene permisos
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd'); 
          
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idCarrera = (int)$idCarrera;
    
    $carrera = $this->gc->dame($idCarrera);
    if (!$carrera){
      $this->session->set_flashdata('resultadoOperacion', "No existe la carrera seleccionada.");
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      redirect('carreras/listar');
    }

    //obtengo lista de materias
    $lista = $carrera->listarMaterias($pagInicio, PER_PAGE);
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("carreras/ver/$idCarrera"),
      'total_rows' => $carrera->cantidadMaterias(),
      'uri_segment' => 4
    ));
    
    //envio datos a la vista
    $departamento = $this->gd->dame($carrera->idDepartamento);
    $director = $this->gu->dame($carrera->idDirectorCarrera);
    $this->data['carrera'] = $carrera;
    $this->data['departamento'] = ($departamento)?$departamento:$this->Departamento;
    $this->data['director'] = ($director)?$director:$this->Usuario;
    $this->data['lista'] = &$lista; //array de datos de las materias de la carrera
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('ver_carrera', $this->data);
  }

  /*
   * Recepción del formulario para agregar nueva carrera
   * POST: idDepartamento, idDirectorCarrera, nombre, plan, publicarInforme, publicarHistorico
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    
    //cargo modelos y librerias necesarias
    $this->load->model('Carrera');
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');
    $this->load->model('Gestor_usuarios','gu');
    
    //leo los datos POST
    $this->Carrera->idDepartamento = (int)$this->input->post('idDepartamento');
    $this->Carrera->idDirectorCarrera = ($this->input->post('idDirectorCarrera')>0) ? $this->input->post('idDirectorCarrera') : NULL;
    $this->Carrera->nombre = $this->input->post('nombre',TRUE);
    $this->Carrera->plan = ($this->input->post('plan')) ? (int)$this->input->post('plan') : date('Y');
    $this->Carrera->publicarInformes = ($this->input->post('publicarInformes')) ? RESPUESTA_SI : RESPUESTA_NO;
    $this->Carrera->publicarHistoricos = ($this->input->post('publicarHistoricos')) ? RESPUESTA_SI : RESPUESTA_NO;
    
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      //agrego carrera y cargo vista para mostrar resultado
      $res = $this->gc->alta( $this->Carrera->idDepartamento,
                              $this->Carrera->idDirectorCarrera,
                              $this->Carrera->nombre,
                              $this->Carrera->plan,
                              $this->Carrera->publicarInformes,
                              $this->Carrera->publicarHistoricos);
      //si la operación se realizó con éxito
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('carreras/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if ($this->Carrera->idDirectorCarrera) $this->Usuario = $this->gu->dame($this->Carrera->idDirectorCarrera);
    if ($this->Carrera->idDepartamento) $this->Departamento = $this->gd->dame($this->Carrera->idDepartamento);
    $this->data['carrera'] = &$this->Carrera; //datos por defecto de una nueva carrera
    $this->data['departamento'] = &$this->Departamento;
    $this->data['director'] = &$this->Usuario;
    $this->data['tituloFormulario'] = 'Nueva Carrera';
    $this->data['urlFormulario'] = site_url('carreras/nueva');
    $this->load->view('editar_carrera', $this->data);
  }

  /*
   * Recepción del formulario para modificar los datos de una carrera
   * POST: idCarrera, idDirectorCarrera, idDepartamento, nombre, plan, publicarInforme, publicarHistorico
   */
  public function modificar($idCarrera=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Usuario');
    $this->load->model('Gestor_departamentos','gd');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_carreras','gc');
    
    //leo los datos POST
    $this->Carrera->idCarrera = (int)$this->input->post('idCarrera');
    $this->Carrera->idDepartamento = (int)$this->input->post('idDepartamento');
    $this->Carrera->idDirectorCarrera = ($this->input->post('idDirectorCarrera')>0) ? $this->input->post('idDirectorCarrera') : NULL;
    $this->Carrera->nombre = $this->input->post('nombre',TRUE);
    $this->Carrera->plan = ($this->input->post('plan')) ? (int)$this->input->post('plan') : date('Y');
    $this->Carrera->publicarInformes = ($this->input->post('publicarInformes')) ? RESPUESTA_SI : RESPUESTA_NO;
    $this->Carrera->publicarHistoricos = ($this->input->post('publicarHistoricos')) ? RESPUESTA_SI : RESPUESTA_NO;
    
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      $idDirectorCarrera = $this->input->post('idDirectorCarrera',TRUE);
      //modifico carrera y cargo vista para mostrar resultado
      $res = $this->gc->modificar($this->Carrera->idCarrera,
                                  $this->Carrera->idDepartamento,
                                  $this->Carrera->idDirectorCarrera,
                                  $this->Carrera->nombre,
                                  $this->Carrera->plan,
                                  $this->Carrera->publicarInformes,
                                  $this->Carrera->publicarHistoricos);
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La modificación de la carrera se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('carreras/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if ($idCarrera == null) redirect('carreras/nueva');
    $this->Carrera = $this->gc->dame((int)$idCarrera);
    if (!$this->Carrera){
        $this->session->set_flashdata('resultadoOperacion', "No existe la carrera seleccionada.");
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('carreras/listar');
    }
    if ($this->Carrera->idDirectorCarrera) $this->Usuario = $this->gu->dame($this->Carrera->idDirectorCarrera);
    if ($this->Carrera->idDepartamento) $this->Departamento = $this->gd->dame($this->Carrera->idDepartamento);
    $this->data['carrera'] = &$this->Carrera;
    $this->data['departamento'] = &$this->Departamento;
    $this->data['director'] = &$this->Usuario;
    $this->data['tituloFormulario'] = 'Modificar Carrera';
    $this->data['urlFormulario'] = site_url('carreras/modificar/'.$idCarrera);
    $this->load->view('editar_carrera', $this->data);
  }

  /*
   * Recepción del formulario para eliminar una carrera
   * POST: idCarrera
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
      redirect('carreras/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gc->baja((int)$this->input->post('idCarrera'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La carrera se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('carreras/listar');
  }

  /*
   * Recepción del formulario para crear una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function asociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    
    //leo datos POST
    $idCarrera = (int)$this->input->post('idCarrera');
    
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->asociarMateria((int)$this->input->post('idMateria'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      } 
    }
    redirect('carreras/ver/'.$idCarrera);
  }

  /*
   * Recepción del formulario para eliminar una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function desasociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    
    //leo datos POST
    $idCarrera = (int)$this->input->post('idCarrera');
    
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera;
      //elimino la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->desasociarMateria((int)$this->input->post('idMateria'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }   
    }
    redirect('carreras/ver/'.$idCarrera);
  }

  /*
   * Método para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar');
      $this->load->model('Carrera');
      $this->load->model('Gestor_carreras','gc');
      $carreras = $this->gc->buscar($buscar);
      echo "\n";
      foreach ($carreras as $carrera) {
        echo  "$carrera->idCarrera\t".
              "$carrera->nombre\t".
              "$carrera->plan\t\n";
      }
    }
  }
  
  /*
   * Método para responder solicitudes AJAX
   * POST: idCarrera, buscar
   */
  public function buscarMateriasAJAX(){
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $idCarrera = $this->input->post('idCarrera',TRUE);
      $buscar = $this->input->post('buscar',TRUE);
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera; 
      $materias = $this->Carrera->buscarMaterias($buscar);
      echo "\n";
      foreach ($materias as $materia) {
        echo  "$materia->idMateria\t".
              "$materia->nombre\t".
              "$materia->codigo\t\n";
      }
    }
  }
}

?>