<?php

/**
 * Controlador para la gestión de encuestas
 */
class Encuestas extends CI_Controller{
  
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
   * Muestra el listado de encuestas.
   */
  public function listar($pagInicio=0){
    if(!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif(!$this->ion_auth->in_group(array('admin','decanos','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('/');
    }
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //obtengo lista de encuestas
    $lista = $this->ge->listar($pagInicio, PER_PAGE);
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("encuestas/listar"),
      'total_rows' => $this->ge->cantidad()
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las encuestas
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_encuestas', $this->data);
  }
  
  /*
   * Recepción del formulario para agregar nueva encuesta
   * POST: idFormulario, anio, cuatrimestre, tipo
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
      redirect('encuestas/listar');
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Encuesta');
    $this->load->model('Formulario');
    $this->load->model('Gestor_encuestas','ge');
    //leo los datos POST
    $this->Encuesta->idFormulario = (int)$this->input->post('idFormulario');
    $this->Encuesta->año = ($this->input->post('anio')) ? (int)$this->input->post('anio') : date('Y');
    $this->Encuesta->cuatrimestre = (int)$this->input->post('cuatrimestre');
    $this->Encuesta->tipo = $this->input->post('tipo');
    $this->Formulario->nombre = $this->input->post('nombreFormulario;');
    //verifico datos POST
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('anio','Año','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
    $this->form_validation->set_rules('cuatrimestre','Periodo/Cuatrimestre','required|is_natural_no_zero|less_than[12]');
    $this->form_validation->set_rules('tipo','Tipo Acceso','required|alpha|exact_length[1]');
    if($this->form_validation->run()){
      //agrego encuesta y cargo vista para mostrar resultado
      $res = $this->ge->alta( $this->Encuesta->idFormulario,
                              $this->Encuesta->tipo,
                              $this->Encuesta->año, 
                              $this->Encuesta->cuatrimestre);
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('encuestas/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    $this->data['encuesta'] = &$this->Encuesta; //datos por defecto de una nueva encuesta
    $this->data['formulario'] = &$this->Formulario;
    $this->data['tituloFormulario'] = 'Nueva Encuesta';
    $this->data['urlFormulario'] = site_url('encuestas/nueva');
    $this->load->view('editar_encuesta', $this->data);
  }

  /*
   * Recepción del formulario para eliminar una encuesta
   * POST: idEncuesta, idFormulario
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
      redirect('encuestas/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_encuestas','ge');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->ge->baja((int)$this->input->post('idEncuesta'), (int)$this->input->post('idFormulario'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La encuesta se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('encuestas/listar');
  }

  /*
   * Recepción del formulario para finalizar una Encuesta
   * POST: idEncuesta, idFormulario
   */
  public function finalizar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('encuestas/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      $this->Encuesta->idEncuesta = (int)$this->input->post('idEncuesta'); 
      $this->Encuesta->idFormulario = (int)$this->input->post('idFormulario');
      //finalizo la encuesta y cargo vista para mostrar resultado
      $res = $this->Encuesta->finalizar();
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con éxito.");
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('encuestas/listar');
      }
      $this->session->set_flashdata('resultadoOperacion', $res);
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
    }
    redirect('encuestas/listar');
  }

  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    $this->form_validation->set_rules('buscar','Buscar','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      $this->load->model('Gestor_encuestas','ge');
      $encuestas = $this->ge->buscar($this->input->post('buscar'));
      echo "\n";
      foreach ($encuestas as $encuesta) {
        echo  "$encuesta->idEncuesta\t".
              "$encuesta->idFormulario\t".
              "$encuesta->año\t".
              "$encuesta->cuatrimestre\t".
              "$encuesta->fechaInicio\t\n";
      }
    }
  }
}

?>