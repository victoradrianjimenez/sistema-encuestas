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
    //leo los datos del usuario logueado
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    //leo los mensajes generados en la página anterior
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
  }
  
  public function index(){
    $this->ver();
  }

  /*
   * Muestra el listado de devoluciones, para una materia.
   * POST: $idCarrera
   */
  public function listar($idCarrera=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','docentes'))){
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
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_devoluciones','gd');
    $this->load->model('Gestor_departamentos','gdep');
    
    //chequeo parámetros de entrada
    $idCarrera = ($this->input->post('idCarrera')) ? (int)$this->input->post('idCarrera') : (int)$idCarrera;
    $pagInicio = (int)$pagInicio;    
      
    if($idCarrera){
      $carrera = $this->gc->dame($idCarrera);
      if (!$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'No existe la carrera seleccionada.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones');
      }
      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gdep->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la carrera
      if (!$this->ion_auth->logged_in()){
        $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para ver el listado de Planes de Mejora.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('devoluciones/listar');
      }
      if(!($this->ion_auth->in_group(array('admin','decanos')) ||
          ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
          ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para listar los Planes de Mejora para esta carrera. Sólo pueden verlos los directores de carrera o las autoridades correspondientes.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones/listar');
      }

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
      $this->load->view('solicitud_lista_devoluciones', $this->data);
    }
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
    else{
      //verifico si es jefe de catedra. Si no es, no puede dar de alta una devolucion
      if (!$this->ion_auth->in_group(array('admin','docentes'))){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación. Solamente el Jefe de cátedra puede dar de alta un Plan de Mejoras.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones/ver');
      }
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Devolucion');
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_devoluciones','gd');
    
    //leo los datos POST
    $this->Devolucion->idMateria = (int)$this->input->post('idMateria');
    $this->Devolucion->idEncuesta = (int)$this->input->post('idEncuesta');
    $this->Devolucion->idFormulario = (int)$this->input->post('idFormulario');
    $this->Devolucion->fortalezas = $this->input->post('fortalezas', TRUE);
    $this->Devolucion->debilidades = $this->input->post('debilidades', TRUE);
    $this->Devolucion->alumnos = $this->input->post('alumnos', TRUE);
    $this->Devolucion->docentes = $this->input->post('docentes', TRUE);
    $this->Devolucion->mejoras = $this->input->post('mejoras', TRUE);
    
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('fortalezas','Fortalezas','alpha_dash_space');
    $this->form_validation->set_rules('debilidades','Debilidades','alpha_dash_space');
    $this->form_validation->set_rules('alumnos','Alumnos','alpha_dash_space');
    $this->form_validation->set_rules('docentes','Docentes','alpha_dash_space');
    $this->form_validation->set_rules('mejoras','Mejoras','alpha_dash_space');
    if($this->form_validation->run()){
      //verifico si es jefe de catedra. Si no es, no puede dar de alta una devolucion
      $this->load->model('Usuario');
      $this->Usuario->id = $this->data['usuarioLogin']->id;
      $datosDocente = $this->Usuario->dameDatosDocente((int)$this->input->post('idMateria'));
      if (!(isset($datosDocente['tipoAcceso']) && $datosDocente['tipoAcceso']==TIPO_ACCESO_JEFE_CATEDRA) ){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación. Solamente el Jefe de cátedra puede dar de alta un Plan de Mejoras.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones/ver');
      }  
      //agrego devolucion y cargo vista para mostrar resultado
      $res = $this->gd->alta( $this->Devolucion->idMateria, $this->Devolucion->idEncuesta, $this->Devolucion->idFormulario, 
                              $this->Devolucion->fortalezas, $this->Devolucion->debilidades, $this->Devolucion->alumnos,
                              $this->Devolucion->docentes, $this->Devolucion->mejoras);
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('devoluciones/ver');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if ($this->Devolucion->idEncuesta && $this->Devolucion->idFormulario) $this->Encuesta = $this->ge->dame($this->Devolucion->idEncuesta, $this->Devolucion->idFormulario);
    if ($this->Devolucion->idMateria) $this->Materia = $this->gm->dame($this->Devolucion->idMateria);
    $this->data['materia'] = &$this->Materia; 
    $this->data['devolucion'] = &$this->Devolucion;
    $this->data['encuesta'] = &$this->Encuesta;
    $this->load->view('editar_devolucion', $this->data);
    return;
  }

  /*
   * Ver una devolucion
   */
  public function ver($idMateria=null, $idEncuesta=null, $idFormulario=null){
    //cargo modelos, librerias, etc.
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Devolucion');
    $this->load->model('Gestor_departamentos','gdep');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_devoluciones','gd');
    
    $idMateria = ($this->input->post('idMateria'))?(int)$this->input->post('idMateria'):(int)$idMateria;
    $idEncuesta = ($this->input->post('idEncuesta'))?(int)$this->input->post('idEncuesta'):(int)$idEncuesta;
    $idFormulario = ($this->input->post('idFormulario'))?(int)$this->input->post('idFormulario'):(int)$idFormulario;
    if ($idMateria && $idEncuesta && $idFormulario){
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $materia = $this->gm->dame($idMateria);
      if (!$encuesta || !$materia){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('devoluciones/listar');
      }   
      //verifico si el usuario tiene permisos para la materia
      if ($materia->publicarDevoluciones != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los planes de mejoras de esta asignatura no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('devoluciones/ver');
        }
        //obtener datos del usuario logueado
        $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id);
        
        $pass = false;
        $carreras = $materia->listarCarreras(); //listar las carreras a la que pertenece la materia
        //verifico si el usuario es un director de alguna carrera a la que pertenece la materia o un jefe de depto
        foreach ($carreras as $carrera) {
          if($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) {$pass=true; break;}
          $departamento = $this->gdep->dame($carrera->idDepartamento);
          if($departamento){
            if($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) {$pass=true; break;}
          }
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($pass) ||
            (!empty($datosDocente)) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver los planes de mejora de esta materia. Sólo pueden verlos los docentes y autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/materia'); 
        }
      }
      //obtengo datos de la devolucion
      $devolucion = $this->gd->dame($idMateria, $idEncuesta, $idFormulario);
      if ($devolucion){
        //envio datos a la vista
        $datos = array(
          'devolucion' => ($devolucion) ? $devolucion : $this->Devolucion,
          'materia' => &$materia,
          'encuesta' => &$encuesta
        );
        $this->load->view('reporte_devolucion', $datos);
        return;
      }
      else{
        $this->data['resultadoOperacion'] = 'No existe un plan de mejoras de la materia para esta encuesta.';
        $this->data['resultadoTipo'] = ALERT_WARNING;
      }
    }
    $this->load->view('solicitud_devoluciones', $this->data);
  }

}
?>