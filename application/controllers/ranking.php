<?php

/**
 * 
 */
class Ranking extends CI_Controller{
  
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

  public function materiasCarrera(){
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idCarrera = (int)$this->input->post('idCarrera');

      //cargo librerias y modelos
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
      
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      if (!$encuesta || !$formulario || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('ranking/materiasCarrera');
      }

      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if (!$this->ion_auth->logged_in()){
        $this->session->set_flashdata('resultadoOperacion', 'Los rankings de esta carrera no son públicos. Debe iniciar sesión para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('ranking/materiasCarrera');
      }
      if(!($this->ion_auth->in_group(array('admin','decanos')) ||
          ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
          ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver rankings de esta carrera. Sólo pueden verlos las autoridades correspondientes.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('ranking/materiasCarrera');
      }

      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      
      //obtener lista de carreras
      $datos_materias = array();
      $materias = $carrera->listarMaterias();
      foreach ($materias as $materia) {
        
        //cargar datos solo de las materias que tengan claves utilizadas (que se dictan en este cuatrimestre)
        $cantidadesClaves = $encuesta->cantidadClavesMateria($materia->idMateria, $idCarrera);
        if ($cantidadesClaves['utilizadas']>0){
          //recorrer secciones
          $datos_materias[$materia->idMateria] = array(
            'materia' => $materia,
            'indices' => array()
          );
          foreach ($secciones as $seccion) {
            if ($seccion->tipo != SECCION_TIPO_ALUMNO){
              $datos_materias[$materia->idMateria]['indices'][$seccion->idSeccion] = $encuesta->indiceSeccionMateria($materia->idMateria, $idCarrera, $seccion->idSeccion);
            }
          }
          $indicesGLobales[$materia->idMateria] = $encuesta->indiceGlobalMateria($materia->idMateria, $idCarrera);
        }
      } 
      
      //ordeno los indices en forma descendiente
      arsort($indicesGLobales);
      
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'carrera' => &$carrera,
        'departamento' => &$departamento,
        'datos_materias' => &$datos_materias,
        'indicesGLobales' => &$indicesGLobales,
        'secciones' => &$secciones
      );
      $this->load->view('ranking_materias_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_ranking_materias_carrera', $this->data);
    }
  }

  
  public function docentesCarrera(){
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idCarrera = (int)$this->input->post('idCarrera');

      //cargo librerias y modelos
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
      
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      if (!$encuesta || !$formulario || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('ranking/materiasCarrera');
      }

      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if (!$this->ion_auth->logged_in()){
        $this->session->set_flashdata('resultadoOperacion', 'Los rankings de esta carrera no son públicos. Debe iniciar sesión para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('ranking/materiasCarrera');
      }
      if(!($this->ion_auth->in_group(array('admin','decanos')) ||
          ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
          ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver rankings de esta carrera. Sólo pueden verlos las autoridades correspondientes.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('ranking/materiasCarrera');
      }

      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      
      //obtener lista de carreras
      $datos_docentes = array();
      $materias = $carrera->listarMaterias();
      foreach ($materias as $materia) {
        //cargar datos solo de las materias que tengan claves utilizadas (que se dictan en este cuatrimestre)
        $cantidadesClaves = $encuesta->cantidadClavesMateria($materia->idMateria, $idCarrera);
        if ($cantidadesClaves['utilizadas']>0){
          
          $docentes = $encuesta->listarDocentesMateria($materia->idMateria, $idCarrera);
        
          foreach ($secciones as $seccion) {
            if ($seccion->tipo == SECCION_TIPO_DOCENTE){
              foreach ($docentes as $docente) {
                $key = $docente->id.'_'.$materia->idMateria;
                $datos_docentes[$key] = array(
                  'docente' => $docente,
                  'seccion' => $seccion,
                  'materia' => $materia,
                );
                $indices[$key] = $encuesta->indiceDocenteMateria($materia->idMateria, $idCarrera, $seccion->idSeccion, $docente->id); 
              }
            }
          }
        }
      } 
      
      //ordeno los indices en forma descendiente
      arsort($indices);
      
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'carrera' => &$carrera,
        'departamento' => &$departamento,
        'datos_docentes' => &$datos_docentes,
        'indices' => &$indices,
        'secciones' => &$secciones
      );
      $this->load->view('ranking_docentes_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_ranking_docentes_carrera', $this->data);
    }
  }
}
?>