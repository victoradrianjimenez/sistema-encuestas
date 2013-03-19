<?php

/**
 * Controlador para la gestión de Devoluciones
 */
class Devoluciones extends CI_Controller {
    
  var $data = array(); //datos para mandar a las vistas

  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Solicitar Plan de Mejoras
   * POST: idMateria, $idCarrera, $idEncuesta, $idMateria
   */
  public function solicitar(){
    //verifico si el usuario tiene permisos para continuar
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
    $this->load->model('Carrera');
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Devolucion');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_devoluciones','gd');
    
    $idMateria = (int)$this->input->post('idMateria');
    $idEncuesta = (int)$this->input->post('idEncuesta');
    $idFormulario = (int)$this->input->post('idFormulario');
    
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $this->ver(1, $idMateria, $idEncuesta, $idFormulario);
      return;

    }
    $this->load->view('solicitud_devoluciones', $this->data);
  }

  /*
   * Muestra el listado de devoluciones, para una materia.
   * POST: idMateria
   */
  public function listar($idCarrera=null,$pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
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
    $idCarrera = ($this->input->post('idCarrera')) ? (int)$this->input->post('idCarrera') : $idCarrera;
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Carrera');
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Devolucion');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_devoluciones','gd');
    if ($idCarrera){
      $carrera = $this->gc->dame($idCarrera);
      if ($carrera){
        //obtengo lista de devoluciones
        $devoluciones = $this->gd->listar($idCarrera, $pagInicio, PER_PAGE);
        $lista = array(); //datos para mandar a la vista
        foreach ($devoluciones as $i => $devolucion) {
          $encuesta = $this->ge->dame($devolucion->idEncuesta, $devolucion->idFormulario);
          $materia = $this->gm->dame($devolucion->idMateria);
          $lista[$i] = array(
            'devolucion' => $devolucion,
            'encuesta' => ($encuesta)?$encuesta:$this->Encuesta,
            'materia' => ($materia)?$materia:$this->Materia
          );
        }
        //genero la lista de links de paginación
        $this->pagination->initialize(array(
          'base_url' => site_url("devoluciones/listar/$idCarrera"),
          'total_rows' => $this->gd->cantidad($idCarrera),
          'uri_segment' => 4
        ));
        //envio datos a la vista
        $this->data['lista'] = &$lista; //array de datos de las devoluciones
        $this->data['carrera'] = &$carrera;
        $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
        $this->load->view('lista_devoluciones', $this->data);
        return;
      }
      else{
        $this->data['resultadoOperacion'] = "No existe una carrera con ID=$idCarrera.";
        $this->data['resultadoTipo'] = ALERT_WARNING;
      }
    }
    $this->load->view('solicitud_lista_devoluciones', $this->data);
  }

  /*
   * Muestra el formulario de edicion de formularios
   * POST: idMateria
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('devoluciones/listar');
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Devolucion');
    $this->load->model('Materia');
    $this->load->model('Usuario');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_devoluciones','gd');
    
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $materia = $this->gm->dame((int)$this->input->post('idMateria'));
    if($this->form_validation->run() && $materia){
      //leo los datos POST
      $this->Devolucion->fortalezas = $this->input->post('fortalezas', TRUE);
      $this->Devolucion->debilidades = $this->input->post('debilidades', TRUE);
      $this->Devolucion->alumnos = $this->input->post('alumnos', TRUE);
      $this->Devolucion->docentes = $this->input->post('docentes', TRUE);
      $this->Devolucion->mejoras = $this->input->post('mejoras', TRUE);
      $this->Devolucion->idEncuesta = (int)$this->input->post('idEncuesta');
      $this->Devolucion->idFormulario = (int)$this->input->post('idFormulario');
      $this->Devolucion->idMateria = (int)$this->input->post('idMateria');
      if ($this->input->post('idEncuesta')){
        $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
        $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
        $this->form_validation->set_rules('fortalezas','Fortalezas','alpha_dash_space');
        $this->form_validation->set_rules('debilidades','Debilidades','alpha_dash_space');
        $this->form_validation->set_rules('alumnos','Alumnos','alpha_dash_space');
        $this->form_validation->set_rules('docentes','Docentes','alpha_dash_space');
        $this->form_validation->set_rules('mejoras','Mejoras','alpha_dash_space');
        if($this->form_validation->run() && $materia){
          //verifico si es jefe de catedra. Si no es, no puede dar de alta una devolucion
          $this->Usuario->id = $this->data['usuarioLogin']->id;
          $datosDocente = $this->Usuario->dameDatosDocente($materia->idMateria);
          if ($this->ion_auth->in_group('docentes') && (!isset($datosDocente['tipoAcceso']) || $datosDocente['tipoAcceso']!=TIPO_ACCESO_JEFE_CATEDRA)){
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación. Solamente el Jefe de cátedra puede dar de alta un Plan de Mejoras.');
            $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
            redirect('devoluciones/listar');
          }
          //agrego devolucion y cargo vista para mostrar resultado
          $res = $this->gd->alta( $this->Devolucion->idMateria, 
                                  $this->Devolucion->idEncuesta, 
                                  $this->Devolucion->idFormulario,
                                  $this->Devolucion->fortalezas,
                                  $this->Devolucion->debilidades,
                                  $this->Devolucion->alumnos,
                                  $this->Devolucion->docentes,
                                  $this->Devolucion->mejoras);
          if (is_numeric($res)){
            $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con éxito. El ID del nuevo plan de mejoras es $res.");
            $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
            redirect('devoluciones/listar');
          }
          $this->data['resultadoOperacion'] = $res;
          $this->data['resultadoTipo'] = ALERT_ERROR;
        }
      }
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      if ($this->Devolucion->idEncuesta && $this->Devolucion->idFormulario) $this->Encuesta = $this->ge->dame($this->Devolucion->idEncuesta, $this->Devolucion->idFormulario);
      $this->data['materia'] = &$materia; 
      $this->data['devolucion'] = &$this->Devolucion;
      $this->data['encuesta'] = &$this->Encuesta;
      $this->load->view('editar_devolucion', $this->data);
      return;
    }
    $this->session->set_flashdata('resultadoOperacion', "No existe la materia con ID=".$this->input->post('idMateria').".");
    $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
    redirect('devoluciones/listar');
  }

  /*
   * Ver una devolucion
   */
  public function ver($idDevolucion=null, $idMateria=null, $idEncuesta=null, $idFormulario=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      return;
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Devolucion');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_devoluciones','gd');
    if ($idDevolucion && $idMateria && $idEncuesta && $idFormulario){
      //obtengo datos de la devolucion
      $encuesta = $this->ge->dame((int)$idEncuesta, (int)$idFormulario);
      $materia = $this->gm->dame((int)$idMateria);
      $devolucion = $this->gd->dame((int)$idDevolucion, (int)$idMateria, (int)$idEncuesta, (int)$idFormulario);
      if (!$devolucion || !$encuesta || !$materia){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones/listar');
      }
      //envio datos a la vista
      $datos = array(
        'devolucion' => &$devolucion,
        'materia' => &$materia,
        'encuesta' => &$encuesta
      );
      $this->load->view('mostrar_devolucion', $datos);
    }
    else show_404();
  }

}
