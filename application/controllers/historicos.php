<?php

/**
 * Controlador para la Gestión de Informes Históricos
 */
class Historicos extends CI_Controller{
  
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

  /*
   * Solicitar y mostrar un informe historico por materia
   */
  public function materia(){
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idPregunta','Pregunta','required|is_natural_no_zero');
    $this->form_validation->set_rules('fechaInicio','Fecha Inicio','required');
    $this->form_validation->set_rules('fechaFin','Fecha Fin','required');
    if($this->form_validation->run()){
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      $idPregunta = (int)$this->input->post('idPregunta');
      $fechaInicio = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaInicio'))));
      $fechaFin = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaFin'))));
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_preguntas','gp');
      
      $pregunta = $this->gp->dame($idPregunta);
      $materia = $this->gm->dame($idMateria);
      $carrera = $this->gc->dame($idCarrera);
      if (!$pregunta || !$materia || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('historicos/materia');
      }
      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if ($materia->publicarHistoricos != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los históricos de esta asignatura no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('historicos/materia');
        }
        //obtener datos del usuario logueado
        $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id); 
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) ||
            (!empty($datosDocente)) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver históricos por materia.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('historicos/materia');
        }
      }
      $historico = $pregunta->historicoMateria($idMateria, $idCarrera, $fechaInicio, $fechaFin);
      if (empty($historico)){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('historicos/materia');
      }
      
      foreach ($historico as $k => $h) {
        $this->Encuesta->idEncuesta = $h['idEncuesta'];
        $this->Encuesta->idFormulario = $h['idFormulario'];
        $claves = $this->Encuesta->cantidadClavesMateria($idMateria, $idCarrera);
        $historico[$k]['cantidad'] = $claves['generadas'];
        $historico[$k]['contestadas'] = $claves['utilizadas']; 
      }

      //datos para enviar a la vista
      $datos = array(
        'datos' => &$historico,
        'pregunta' => &$pregunta,
        'carrera' => &$carrera,
        'materia' => &$materia,
        'opciones' => $pregunta->listarOpciones(),
        'departamento' => &$departamento,
        'fechaInicio' => &$fechaInicio,
        'fechaFin' => &$fechaFin
      );
      $this->load->view('historico_materia', $datos);
    }
    else{
      $this->load->view('solicitud_historico_materia', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe historico por carrera
   */
  public function carrera(){
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idPregunta','Pregunta','required|is_natural_no_zero');
    $this->form_validation->set_rules('fechaInicio','Fecha Inicio','required');
    $this->form_validation->set_rules('fechaFin','Fecha Fin','required');
    if($this->form_validation->run()){
      $idCarrera = (int)$this->input->post('idCarrera');
      $idPregunta = (int)$this->input->post('idPregunta');
      $fechaInicio = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaInicio'))));
      $fechaFin = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaFin'))));
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_preguntas','gp');
      
      $pregunta = $this->gp->dame($idPregunta);
      $carrera = $this->gc->dame($idCarrera);
      if (!$pregunta || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('historicos/carrera');
      }
      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la carrera
      if ($carrera->publicarHistoricos != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los históricos de esta carrera no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('historicos/carrera');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver históricos por carrera.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('historicos/carrera');
        }
      }
      $historico = $pregunta->historicoCarrera($idCarrera, $fechaInicio, $fechaFin);
      if (empty($historico)){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('historicos/carrera');
      }
      
      foreach ($historico as $k => $h) {
        $this->Encuesta->idEncuesta = $h['idEncuesta'];
        $this->Encuesta->idFormulario = $h['idFormulario'];
        $claves = $this->Encuesta->cantidadClavesCarrera($idCarrera);
        $historico[$k]['cantidad'] = $claves['generadas'];
        $historico[$k]['contestadas'] = $claves['utilizadas']; 
      }

      //datos para enviar a la vista
      $datos = array(
        'datos' => &$historico,
        'pregunta' => &$pregunta,
        'carrera' => &$carrera,
        'opciones' => $pregunta->listarOpciones(),
        'departamento' => &$departamento,
        'fechaInicio' => &$fechaInicio,
        'fechaFin' => &$fechaFin
      );
      
      $this->load->view('historico_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_historico_carrera', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe historico por departamento
   */
  public function departamento(){
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','required|is_natural_no_zero');
    $this->form_validation->set_rules('idPregunta','Pregunta','required|is_natural_no_zero');
    $this->form_validation->set_rules('fechaInicio','Fecha Inicio','required');
    $this->form_validation->set_rules('fechaFin','Fecha Fin','required');
    if($this->form_validation->run()){
      $idDepartamento = (int)$this->input->post('idDepartamento');
      $idPregunta = (int)$this->input->post('idPregunta');
      $fechaInicio = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaInicio'))));
      $fechaFin = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaFin'))));
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Departamento');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_preguntas','gp');
      
      $pregunta = $this->gp->dame($idPregunta);
      $departamento = $this->gd->dame($idDepartamento);
      if (!$pregunta || !$departamento){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('historicos/departamento');
      }
      //verifico si el usuario tiene permisos para el departamento
      if ($departamento->publicarHistoricos != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los históricos de este departamento no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('historicos/departamento');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver históricos por departamento.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('historicos/departamento');
        }
      }
      $historico = $pregunta->historicoDepartamento($idDepartamento, $fechaInicio, $fechaFin);
      if (empty($historico)){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('historicos/departamento');
      }

      foreach ($historico as $k => $h) {
        $this->Encuesta->idEncuesta = $h['idEncuesta'];
        $this->Encuesta->idFormulario = $h['idFormulario'];
        $claves = $this->Encuesta->cantidadClavesDepartamento($idDepartamento);
        $historico[$k]['cantidad'] = $claves['generadas'];
        $historico[$k]['contestadas'] = $claves['utilizadas']; 
      }
      
      //datos para enviar a la vista
      $datos = array(
        'datos' => &$historico,
        'pregunta' => &$pregunta,
        'opciones' => $pregunta->listarOpciones(),
        'departamento' => &$departamento,
        'fechaInicio' => &$fechaInicio,
        'fechaFin' => &$fechaFin
      );
      
      $this->load->view('historico_departamento', $datos);
    }
    else{
      $this->load->view('solicitud_historico_departamento', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe historico por facultad
   */
  public function facultad(){
    //verifico datos POST
    $this->form_validation->set_rules('idPregunta','Pregunta','required|is_natural_no_zero');
    $this->form_validation->set_rules('fechaInicio','Fecha Inicio','required');
    $this->form_validation->set_rules('fechaFin','Fecha Fin','required');
    if($this->form_validation->run()){
      $idPregunta = (int)$this->input->post('idPregunta');
      $fechaInicio = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaInicio'))));
      $fechaFin = date('Y-m-d', strtotime(str_replace('/','-',$this->input->post('fechaFin'))));
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_preguntas','gp');
      
      $pregunta = $this->gp->dame($idPregunta);
      if (!$pregunta){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('historicos/facultad');
      }
      //verifico si el usuario tiene permisos para la facultad
      if ($this->config->config['publicarHistoricos']){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los históricos de la facultad no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('historicos/facultad');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver históricos por facultad.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('historicos/facultad');
        }
      }
      $historico = $pregunta->historicoFacultad($fechaInicio, $fechaFin);
      if (empty($historico)){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('historicos/facultad');
      }

      foreach ($historico as $k => $h) {
        $this->Encuesta->idEncuesta = $h['idEncuesta'];
        $this->Encuesta->idFormulario = $h['idFormulario'];
        $claves = $this->Encuesta->cantidadClavesFacultad();
        $historico[$k]['cantidad'] = $claves['generadas'];
        $historico[$k]['contestadas'] = $claves['utilizadas']; 
      }
      
      //datos para enviar a la vista
      $datos = array(
        'datos' => &$historico,
        'pregunta' => &$pregunta,
        'opciones' => $pregunta->listarOpciones(),
        'fechaInicio' => &$fechaInicio,
        'fechaFin' => &$fechaFin
      );
      
      $this->load->view('historico_facultad', $datos);
    }
    else{
      $this->load->view('solicitud_historico_facultad', $this->data);
    }
  } 
}
?>