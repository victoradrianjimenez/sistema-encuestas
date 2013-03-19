<?php

/**
 * 
 */
class Historicos extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  
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
   * Obtener datos de una seccion para mostrar en el informe historico por materia
   * Última revisión: 2012-02-09 7:32 p.m.
   */
  private function _dameDatosSeccionMateria($seccion, $encuesta, $idDocente, $idMateria, $idCarrera){
      $items = $seccion->listarItemsCarrera($idCarrera);
      $datos_items = array();
      foreach ($items as $k => $item) {
        switch ($item->tipo) {
        case 'S': case 'M': case 'N':
          $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->idPregunta, $idDocente, $idMateria, $idCarrera);
          break;
        case 'T': case 'X':
          $datos_respuestas = $encuesta->textosPreguntaMateria($item->idPregunta, $idMateria, $idCarrera);
          break;
        }
        $datos_items[$k] = array(
          'item' => $item,
          'respuestas' => $datos_respuestas
        );
      }
      return $datos_items;
  }
  
  /*
   * Solicitar y mostrar un informe historico por materia
   * Última revisión: 2012-02-09 7:39 p.m.
   */
  public function materia(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
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
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Usuario');
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Usuario');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_usuarios','gu');

      $this->load->model('Pregunta');
      $this->load->model('Gestor_preguntas','gp');
      
      $pregunta = $this->gp->dame($idPregunta);
      $materia = $this->gm->dame($idMateria);
      $carrera = $this->gc->dame($idCarrera);
      if (!$pregunta || !$materia || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('historicos/materia');
      }
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $historico = $pregunta->historicoMateria($idMateria, $idCarrera, $fechaInicio, $fechaFin);
      if (empty($historico)){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('historicos/materia');
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
   * Última revisión: 2012-02-09 8:17 p.m.
   */
  public function carrera(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    if($this->form_validation->run() && sscanf($this->input->post('encuesta'), "%d_%d",$idEncuesta, $idFormulario) == 2){
      $idCarrera = (int)$this->input->post('idCarrera');
      $indicesSecciones = (bool)$this->input->post('indicesSecciones');
      $indiceGlobal = (bool)$this->input->post('indiceGlobal');
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Carrera');
      $this->load->model('Formulario');
      $this->load->model('Departamento');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_encuestas','ge');
      //obtener datos de la encuesta
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);

      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['items'] = array();
        $datos_secciones[$i]['indice'] = ($indicesSecciones)?$encuesta->indiceSeccionCarrera($idCarrera, $seccion->idSeccion):null;        
        $items = $seccion->listarItemsCarrera($idCarrera);
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $encuesta->respuestasPreguntaCarrera($item->idPregunta, $idCarrera)
            );
            break;
          default:
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => null
            );
            break;
          }
        }
      }
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'carrera' => &$carrera,
        'departamento' => &$departamento,
        'claves' => $encuesta->cantidadClavesCarrera($idCarrera),
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalCarrera($idCarrera):null,
        'secciones' => &$datos_secciones
      );
      $this->load->view('historico_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_historico_carrera', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe historico por departamento
   * Última revisión: 2012-02-09 8:17 p.m.
   */
  public function departamento(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    $tmp = $this->input->post('encuesta');
    if($this->form_validation->run() && sscanf($tmp, "%d_%d",$idEncuesta, $idFormulario) == 2){
      $idDepartamento = (int)$this->input->post('idDepartamento');
      
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Departamento');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_encuestas','ge');
      //obtener datos de la encuesta
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $departamento= $this->gd->dame($idDepartamento);
      $secciones = $formulario->listarSecciones();
      
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['items'] = array();
        $items = $seccion->listarItems();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $encuesta->respuestasPreguntaDepartamento($item->idPregunta, $idDepartamento)
            );
            break;
          default:
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => null
            );
            break;
          }
        }
      }
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'departamento' => &$departamento,
        'claves' => $encuesta->cantidadClavesDepartamento($idDepartamento),
        'secciones' => &$datos_secciones
      );
      $this->load->view('historico_departamento', $datos);
    }
    else{
      $this->load->view('solicitud_historico_departamento', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe historico por facultad
   * Última revisión: 2012-02-10 1:42 p.m.
   */
  public function facultad(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    $tmp = $this->input->post('encuesta');
    if($this->form_validation->run() && sscanf($tmp, "%d_%d",$idEncuesta, $idFormulario) == 2){
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_encuestas','ge');
  
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $secciones = $formulario->listarSecciones();
      
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['items'] = array();
        $items = $seccion->listarItems();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $encuesta->respuestasPreguntaFacultad($item->idPregunta)
            );
            break;
          default:
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => null
            );
            break;
          }
        }
      }
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'secciones' => &$datos_secciones
      );
      $this->load->view('historico_facultad', $datos);
    }
    else{
      $this->load->view('solicitud_historico_facultad', $this->data);
    }
  }
}

?>