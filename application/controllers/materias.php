<?php

/**
 * 
 */
class Materias extends CI_Controller{
  
  var $data = array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    $this->data['resultadoTipo'] = ALERT_ERROR;
    $this->data['resultadoOperacion'] = null;
  }
  
  
  public function index(){
    $this->listar();
  }
  
  
  /*
   * Muestra el listado de materias.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    
    //obtengo lista de materias
    $lista = $this->gm->listar($pagInicio, PER_PAGE);
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("materias/listar"),
      'total_rows' => $this->gm->cantidad()
    ));

    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las materias
    $this->data['materia'] = &$this->Materia; //datos por defecto de una  materia
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->load->view('lista_materias', $this->data);
  }


  /*
   * Ver y editar datos relacionados a una materia
   */
  public function ver($idMateria=null, $pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idMateria = (int)$idMateria;

    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $materia = $this->gm->dame($idMateria);
    if ($materia){
      //obtengo lista de datos de docentes
      $lista = $materia->listarDocentes($pagInicio, PER_PAGE);
      //genero la lista de links de paginación
      $this->pagination->initialize(array(
        'base_url' => site_url("materias/ver/$idMateria"),
        'total_rows' => $materia->cantidadDocentes(),
        'uri_segment' => 4
      ));
      $this->data['lista'] = &$lista;
      $this->data['materia'] = &$materia;
      $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
      $this->load->view('ver_materia', $this->data);
    }
    else{
      show_error('El Identificador de Materia no es válido.');
    }
  }


  /*
   * Recepción del formulario para agregar  materia
   * POST: nombre, codigo, alumnos, publicarInforme, publicarDevoluciones, publicarHistorico
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('materias/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_rules('alumnos','Alumnos','is_natural|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');      
      //agrego materia y cargo vista para mostrar resultado
      $res = $this->gm->alta( $this->input->post('nombre',TRUE), 
                              $this->input->post('codigo',TRUE), 
                              $this->input->post('alumnos',TRUE),
                              (bool)$this->input->post('publicarInforme'), 
                              (bool)$this->input->post('publicarHistorico'), 
                              (bool)$this->input->post('publicarDevoluciones'));
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con éxito. El ID de la nueva materia es $res.");
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('materias/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    $this->load->model('Materia');
    $this->Carrera->plan = date('Y');
    $this->data['materia'] = &$this->Materia; //datos por defecto de una nueva materia
    $this->data['tituloFormulario'] = 'Nueva Materia';
    $this->data['urlFormulario'] = site_url('materias/nueva');
    $this->load->view('editar_materia', $this->data);
  }


  /*
   * Recepción del formulario para eliminar una materia
   * POST: idMateria
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('materias/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gm->baja((int)$this->input->post('idMateria'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La materia se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('materias/listar');
  }


  /*
   * Recepción del formulario para modificar los datos de una materia
   * POST: idMateria, nombre, codigo, alumnos, publicarInforme, publicarDevoluciones, publicarHistorico
   */
  public function modificar($idMateria=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('materias/listar');
    }
    $this->load->model('Gestor_materias','gm');
    
    //verifico datos POST
    $idMateria = ($this->input->post('idMateria')) ? (int)$this->input->post('idMateria') : (int)$idMateria;
    $this->form_validation->set_rules('idMateria', 'Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_rules('alumnos','Alumnos','is_natural|required');        
    if($this->form_validation->run()){
      //modifico Materia y cargo vista para mostrar resultado
      $res = $this->gm->modificar($idMateria, 
                                  $this->input->post('nombre',TRUE), 
                                  $this->input->post('codigo',TRUE), 
                                  (int)$this->input->post('alumnos'),
                                  (bool)$this->input->post('publicarInforme'), 
                                  (bool)$this->input->post('publicarHistorico'), 
                                  (bool)$this->input->post('publicarDevoluciones'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La modificación de la materia se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('materias/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    if ($idMateria==0) redirect('materias/nueva');
    //obtengo datos de la materia
    $this->load->model('Materia');
    $materia = $this->gm->dame((int)$idMateria);
    $this->data['materia'] =($materia)?$materia:$this->Materia;
    $this->data['tituloFormulario'] = 'Modificar Materia';
    $this->data['urlFormulario'] = site_url('materias/modificar/'.$idMateria);
    $this->load->view('editar_materia', $this->data);
  }


  /*
   * Recepción del formulario para crear una asociacion entre un docente y una materia
   * POST: idDocente, idMateria, ordenFormulario, cargo
   */
  public function asociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('materias/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDocente','Docente','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('ordenFormulario','Orden en formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('cargo','Cargo','alpha_dash_space|max_length[40]');
    $idMateria = (int)$this->input->post('idMateria');
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->idMateria = $idMateria;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->asociarDocente((int)$this->input->post('idDocente'), 
                                            (int)$this->input->post('ordenFormulario'), 
                                            $this->input->post('cargo', TRUE));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('materias/ver/'.$idMateria);
  }


  /*
   * Recepción del formulario para eliminar una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria
   */
  public function desasociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('materias/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDocente','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Carrera','is_natural_no_zero|required');
    $idMateria = (int)$this->input->post('idMateria');
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->idMateria = $idMateria;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->desasociarDocente((int)$this->input->post('idDocente'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('materias/ver/'.$idMateria);
  }

  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar');
      $this->load->model('Materia');
      $this->load->model('Gestor_materias','gm');
      $materias = $this->gm->buscar($buscar);
      echo "\n";
      foreach ($materias as $materia) {
        echo  "$materia->idMateria\t".
              "$materia->nombre\t".
              "$materia->codigo\t\n";
      }
    }
  }
  
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: idMateria, buscar
   
  public function listarCarrerasAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->load->model('Materia');
      $this->Materia->idMateria = $this->input->post('idMateria');
      $carreras = $this->Materia->listarCarreras();
      echo "\n";
      foreach ($carreras as $carrera) {
        echo  "$carrera->idCarrera\t".
              "$carrera->nombre\t".
              "$carrera->plan\t\n";
      }
    }
  }
   */
}
?>