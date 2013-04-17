<?php

/**
 * 
 */
class Informes extends CI_Controller{
  
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

  private function _filtrarListaDocentes($materia, $carrera, $departamento, $listaDocentes){
    //verifico si el usuario tiene permisos para la materia
    if ($materia->publicarInformes != RESPUESTA_SI){
      if ($this->ion_auth->logged_in()){
        $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id);    
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) ||
            (!empty($datosDocente) && $datosDocente['tipoAcceso']==TIPO_ACCESO_JEFE_CATEDRA) )){    
          //mostrar solo resultado del usuario logueado
          $docentes = array();
          if ($this->data['usuarioLogin']){
            //si hay un usuario logueado, buscar entre los docentes de la encuesta
            foreach ($listaDocentes as $docente) {
              if ($docente->id == $this->data['usuarioLogin']->id){$docentes[0] = $docente; break;} 
            }
          }
          return $docentes;
        }
        else{
          return $listaDocentes; //mostrar resultados para todos los docentes
        }
      }
      return array();
    }
    return $listaDocentes; //mostrar resultados para todos los docentes
  }

  /*
   * Auxiliar. Obtener datos de una seccion para mostrar en el informe por materia (funcion auxiliar)
   */
  private function _dameDatosSeccionMateria($seccion, $encuesta, $idDocente, $idMateria, $idCarrera){
      $items = $seccion->listarItemsCarrera($idCarrera);
      $datos_items = array();
      foreach ($items as $k => $item) {
        switch ($item->tipo) {
        case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
          $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->idPregunta, $idDocente, $idMateria, $idCarrera);
          //transformo a valores promedios
          $cnt = 0;
          foreach ($datos_respuestas as $r) {
            $cnt += (int)$r['cantidad'];
          }
          foreach ($datos_respuestas as $pos => $r) {
            $datos_respuestas[$pos]['cantidad'] = ($cnt>0) ? round($r['cantidad']/$cnt*100,2).'%':'-%';
          }
          break;
        case TIPO_TEXTO_SIMPLE: case TIPO_TEXTO_MULTILINEA:
          $datos_respuestas = $encuesta->textosPreguntaMateria($item->idPregunta, $idDocente, $idMateria, $idCarrera);
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
   * Solicitar y mostrar un informe por clave (es decir, por alumno)
   */
  public function clave(){
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('idClave','Clave','required|is_natural_no_zero');
    
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idClave = (int)$this->input->post('idClave');
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      $indicesDocentes = (bool)$this->input->post('indicesDocentes');
      $indicesSecciones = (bool)$this->input->post('indicesSecciones');
      $indiceGlobal = (bool)$this->input->post('indiceGlobal');
      
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Usuario');
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Clave');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');

      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      $clave = $encuesta->dameClave($idClave, $idMateria, $idCarrera);
      if (!$encuesta || !$formulario || !$carrera || !$materia || !$clave){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/clave');
      }
      if (!$clave->utilizada){
        $this->session->set_flashdata('resultadoOperacion', 'La clave de acceso seleccionada todavia no fue utilizada.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/clave');
      }
      
      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if (!$this->ion_auth->logged_in()){
        $this->session->set_flashdata('resultadoOperacion', 'Los informes por clave de acceso no son públicos. Debe iniciar sesión para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('informes/clave');
      }
      $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id);
      if(!($this->ion_auth->in_group(array('admin','decanos')) ||
          ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
          ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) ||
          (!empty($datosDocente)) )){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por clave de acceso. Sólo pueden verlos los docentes y autoridades correspondientes.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/clave');
      }

      //obtener la lista de docentes que participa en la encuesta. Luego filtrar para mostrar en el informe, dependiendo de que permisos tenga el usuario logueado
      $listaDocentes = $encuesta->listarDocentesMateria($idMateria, $idCarrera);
      $docentes = $this->_filtrarListaDocentes($materia, $carrera, $departamento, $listaDocentes);
      
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $this->Usuario->id = 0; //importante. Es el id de un docente no existente.
      
      //recorrer secciones, docentes, y preguntas obteniendo resultados
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['subsecciones'] = array();
        $datos_secciones[$i]['indice'] = ($indicesSecciones)?$encuesta->indiceSeccionClave($idClave, $idMateria, $idCarrera, $seccion->idSeccion):null;
        switch ($seccion->tipo){
        //si la sección es referida a docentes
        case SECCION_TIPO_DOCENTE:
          foreach ($docentes as $j => $docente) {
            $items = $seccion->listarItemsCarrera($idCarrera);
            $datos_items = array();
            foreach ($items as $k => $item) {
              $datos_items[$k] = array(
                'item' => $item,
                'respuestas' => $clave->respuestaPregunta($item->idPregunta, $docente->id)
              );
            }            
            $datos_secciones[$i]['subsecciones'][$j] = array(
              'docente' => $docente,
              'items' => $datos_items,
              'indice' => ($indicesDocentes)?$encuesta->indiceDocenteClave($idClave, $idMateria, $idCarrera, $seccion->idSeccion, $docente->id):null
            );
          }
          break;
        //si la sección es referida a la materia (sección comun)
        case SECCION_TIPO_NORMAL:
          $items = $seccion->listarItemsCarrera($idCarrera);
          $datos_items = array();
          foreach ($items as $k => $item) {
            $datos_items[$k] = array(
              'item' => $item,
              'respuestas' => $clave->respuestaPregunta($item->idPregunta, 0)
            );
          }
          $datos_secciones[$i]['subsecciones'][0] =  array(
            'docente' => $this->Usuario,
            'items' => $datos_items,
            'indice' => null
          );
          break;
        }
      }
      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'carrera' => &$carrera,
        'departamento' => &$departamento,
        'materia' => &$materia,
        'indice' => ($indiceGlobal) ? $encuesta->indiceGlobalClave($idClave, $idMateria, $idCarrera) : null,
        'clave' => &$clave,
        'claveAnteriorPosterior' => $encuesta->clavesAnteriorPosterior($idClave, $idCarrera, $idMateria), 
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_clave', $datos);
    }
    else{
      $this->load->view('solicitud_informe_clave', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe por materia
   */
  public function materia(){
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      $indicesDocentes = (bool)$this->input->post('indicesDocentes');
      $indicesSecciones = (bool)$this->input->post('indicesSecciones');
      $indiceGlobal = (bool)$this->input->post('indiceGlobal');
      $graficos = (bool)$this->input->post('graficos');
      
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
      
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      if (!$encuesta || !$formulario || !$carrera || !$materia){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/materia');
      }

      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if ($materia->publicarInformes != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los informes de esta asignatura no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/materia');
        }
        //obtener datos del usuario logueado
        $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id);
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) ||
            (!empty($datosDocente)) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por materia. Sólo pueden verlos los docentes y autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/materia'); 
        }
      }

      //si no hay datos para mostrar, finalizar
      $cantidadesClaves = $encuesta->cantidadClavesMateria($idMateria, $idCarrera);
      if ($cantidadesClaves['generadas']<1){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('informes/materia');
      }
      
      //obtener la lista de docentes que participa en la encuesta. Luego filtrar para mostrar en el informe, dependiendo de que permisos tenga el usuario logueado
      $listaDocentes = $encuesta->listarDocentesMateria($idMateria, $idCarrera);      
      $docentes = $this->_filtrarListaDocentes($materia, $carrera, $departamento, $listaDocentes);
      
      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $this->Usuario->id = 0; //importante. Es el id de un docente no existente.
      
      //recorrer secciones, docentes, y preguntas obteniendo resultados
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i] = array(
          'seccion' => $seccion,
          'subsecciones' => array(),
          'indice' => ($indicesSecciones) ? $encuesta->indiceSeccionMateria($idMateria, $idCarrera, $seccion->idSeccion) : null
        );
        switch ($seccion->tipo){
        case SECCION_TIPO_DOCENTE:
          foreach ($docentes as $j => $docente) {
            $datos_secciones[$i]['subsecciones'][$j] = array(
              'docente' => $docente,
              'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, $docente->id, $idMateria, $carrera->idCarrera),
              'indice' => ($indicesDocentes) ? $encuesta->indiceDocenteMateria($idMateria, $idCarrera, $seccion->idSeccion, $docente->id) : null
            );
          }
          break;
        case SECCION_TIPO_NORMAL:
          $datos_secciones[$i]['subsecciones'][0] =  array(
            'docente' => $this->Usuario,
            'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, 0, $idMateria, $carrera->idCarrera),
            'indice' => null
          );
          break;
        }
      }

      //datos para enviar a la vista
      $datos = array(
        'encuesta' => &$encuesta,
        'formulario' => &$formulario,
        'carrera' => &$carrera,
        'departamento' => &$departamento,
        'materia' => &$materia,
        'claves' => &$cantidadesClaves, 
        'indice' => ($indiceGlobal) ? $encuesta->indiceGlobalMateria($idMateria, $idCarrera) : null,
        'graficos' => &$graficos,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_materia', $datos);
    }
    else{
      $this->load->view('solicitud_informe_materia', $this->data);
    }
  }


  /*
   * Solicitar y mostrar un informe por carrera
   */
  public function carrera(){
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idCarrera = (int)$this->input->post('idCarrera');
      $indicesSecciones = (bool)$this->input->post('indicesSecciones');
      $indiceGlobal = (bool)$this->input->post('indiceGlobal');
      $graficos = (bool)$this->input->post('graficos');
      
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
 
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      if (!$encuesta || !$formulario || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/carrera');
      }
      
      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la carrera
      if ($carrera->publicarInformes != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los informes de esta carrera no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/carrera');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por carrera. Sólo pueden verlos las autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/carrera');
        }
      }
      
      //si no hay datos para mostrar, finalizar
      $cantidadesClaves =  $encuesta->cantidadClavesCarrera($idCarrera);
      if ($cantidadesClaves['generadas']<1){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('informes/carrera');
      }
      
      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i] =  array(
          'seccion' => $seccion,
          'items' => array(),
          'indice' => ($indicesSecciones) ? $encuesta->indiceSeccionCarrera($idCarrera, $seccion->idSeccion) : null
        );        
        $items = $seccion->listarItemsCarrera($idCarrera);
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
            $respuestas = $encuesta->respuestasPreguntaCarrera($item->idPregunta, $idCarrera);
            //transformo a valores promedios
            $cnt = 0;
            foreach ($respuestas as $r) {
              $cnt += (int)$r['cantidad'];
            }
            foreach ($respuestas as $pos => $r) {
              $respuestas[$pos]['cantidad'] = ($cnt>0) ? round($r['cantidad']/$cnt*100,2).'%':'-%';
            }
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $respuestas
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
        'claves' => &$cantidadesClaves,
        'indice' => ($indiceGlobal) ? $encuesta->indiceGlobalCarrera($idCarrera) : null,
        'graficos' => &$graficos,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_informe_carrera', $this->data);
    }
  }


  /*
   * Solicitar y mostrar un informe por departamento
   */
  public function departamento(){
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idDepartamento = (int)$this->input->post('idDepartamento');
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $graficos = (bool)$this->input->post('graficos');
      
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
      
      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $departamento = $this->gd->dame($idDepartamento);
      
      if (!$encuesta || !$formulario || !$departamento){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/departamento');
      }

      //verifico si el usuario tiene permisos para el departamento
      if ($departamento->publicarInformes != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los informes de este departamento no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/departamento');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por departamento. Sólo pueden verlos las autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/departamento');
        }
      }
      
      //si no hay datos para mostrar, finalizar
      $cantidadesClaves =  $encuesta->cantidadClavesDepartamento($idDepartamento);
      if ($cantidadesClaves['generadas']<1){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('informes/departamento');
      }
      
      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSecciones();
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['items'] = array();
        $items = $seccion->listarItems();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
            $respuestas = $encuesta->respuestasPreguntaDepartamento($item->idPregunta, $idDepartamento);
            //transformo a valores promedios
            $cnt = 0;
            foreach ($respuestas as $r) {
              $cnt += (int)$r['cantidad'];
            }
            foreach ($respuestas as $pos => $r) {
              $respuestas[$pos]['cantidad'] = ($cnt>0) ? round($r['cantidad']/$cnt*100,2).'%':'-%';
            }
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $respuestas
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
        'claves' => &$cantidadesClaves,
        'graficos' => &$graficos,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_departamento', $datos);
    }
    else{
      $this->load->view('solicitud_informe_departamento', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe por facultad
   */
  public function facultad(){
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $graficos = (bool)$this->input->post('graficos');
      
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_encuestas','ge');

      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      if (!$encuesta || !$formulario){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/facultad');
      }
      
      //verifico si el usuario tiene permisos para la facultad
      if ($this->config->config['publicarInformes']){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Los informes de la facultad no son públicos. Debe iniciar sesión para realizar esta operación.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/facultad');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por facultad. Sólo pueden verlos las autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/facultad');
        }
      }

      //si no hay datos para mostrar, finalizar
      $cantidadesClaves =  $encuesta->cantidadClavesFacultad();
      if ($cantidadesClaves['generadas']<1){
        $this->session->set_flashdata('resultadoOperacion', 'No hay datos para mostrar.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        redirect('informes/facultad');
      }
      
      $secciones = $formulario->listarSecciones();
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i] = array (
          'seccion' => $seccion,
          'items' => array()
        );
        $items = $seccion->listarItems();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
            $respuestas = $encuesta->respuestasPreguntaFacultad($item->idPregunta);
            //transformo a valores promedios
            $cnt = 0;
            foreach ($respuestas as $r) {
              $cnt += (int)$r['cantidad'];
            }
            foreach ($respuestas as $pos => $r) {
              $respuestas[$pos]['cantidad'] = ($cnt>0) ? round($r['cantidad']/$cnt*100,2).'%':'-%';
            }
            $datos_secciones[$i]['items'][$k] = array(
              'item' => $item,
              'respuestas' => $respuestas
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
        'claves' => &$cantidadesClaves,
        'graficos' => &$graficos,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_facultad', $datos);
    }
    else{
      $this->load->view('solicitud_informe_facultad', $this->data);
    }
  }


  /*
   * Solicitar y mostrar un informe por facultad
   */
  public function archivoMateria(){
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('tipo','Tipo de archivo','required|alpha');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      $tipo = $this->input->post('tipo');
      
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Usuario');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Carrera');
      $this->load->model('Materia');
      $this->load->model('Departamento');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->library('PHPExcel/PHPExcel');

      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      if (!$encuesta || !$formulario || !$carrera || !$materia){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/materia');
      }

      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la materia
      if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Para descargar la planilla de cálculo primero debe iniciar sesión.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/materia');
      }
      $datosDocente = $materia->dameDatosDocente($this->data['usuarioLogin']->id);
      if(!($this->ion_auth->in_group(array('admin','decanos')) ||
          ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
          ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) ||
          (!empty($datosDocente)) )){
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para descargar la planilla de cálculo. Sólo los docentes de cátedra o las autoridades correspondientes pueden hacerlo.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/materia');
      }
      
      //obtengo lista de docentes, que depende de que permisos tenga el usuario logueado
      $listaDocentes = $encuesta->listarDocentesMateria($idMateria, $idCarrera);
      $docentes = $this->_filtrarListaDocentes($materia, $carrera, $departamento, $listaDocentes);

      //Iniciar la planilla de cálculo
      $this->phpexcel->getProperties()->setCreator('Sistema Encuestas Vía Web')
                     ->setLastModifiedBy('Sistema Encuestas Vía Web')
                     ->setTitle('Datos Encuestas '.$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setSubject('Datos Encuestas '.$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setDescription('Datos de las encuestas para la asignatura '.$materia->nombre.' ('.$materia->codigo.')');
      
      //genero primera pagina con datos generales
      $this->phpexcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, 1, 'DATOS DE LAS ENCUESTAS')
        ->setCellValueByColumnAndRow(0, 2, "Carrera: $carrera->nombre, plan $carrera->plan")
        ->setCellValueByColumnAndRow(0, 3, "Asignatura: $materia->nombre ($materia->codigo)")
        ->setCellValueByColumnAndRow(0, 4, "Período de encuesta: $encuesta->año, ".PERIODO.' '.$encuesta->cuatrimestre)
        ->setCellValueByColumnAndRow(0, 5, 'Fecha de inicio: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaInicio)))
        ->setCellValueByColumnAndRow(0, 6, 'Fecha de cierre: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaFin)))
        ->setCellValueByColumnAndRow(0, 7, "Formulario: $formulario->titulo")
        ->setCellValueByColumnAndRow(0, 9, 'Esta planilla de cálculo contiene los datos de las encuestas, organizados de la siguiente forma:')
        ->setCellValueByColumnAndRow(0, 10, '* La primera hoja contiene los datos generales de la encuesta.')
        ->setCellValueByColumnAndRow(0, 11, '* La segunda hoja contiene el listado de preguntas que conforman el formulario utilizado en la encuesta.')
        ->setCellValueByColumnAndRow(0, 12, '* La tercer hoja contiene el listado de docentes que participaron en la encuesta.')
        ->setCellValueByColumnAndRow(0, 13, 'El resto de las hojas contienes los datos de cada sección del formulario, que tienen el siguiente formato:')
        ->setCellValueByColumnAndRow(0, 14, '* La primer columna contiene el identificador de la clave de acceso que se usó para responder la encuesta.')
        ->setCellValueByColumnAndRow(0, 15, '* La segunda columna contiene el identidicador del docente al cual se refiere la pregunta. Si este valor es vacío significa que la pregunta es referida a la cátedra.')
        ->setCellValueByColumnAndRow(0, 16, '* El resto de las columnas muestran las respuestas dadas para una pregunta en particular. En el encabezado de las columnas se muestra el identidicador de la pregunta.')
        ->setTitle('Información');
        
      //creo la hoja para el listado de preguntas
      $this->phpexcel->createSheet();
      $worksheet = $this->phpexcel->setActiveSheetIndex(1)
        ->setCellValueByColumnAndRow(0, 1, 'LISTADO DE PREGUNTAS')
        ->setCellValueByColumnAndRow(0, 3, 'ID Pregunta')
        ->setCellValueByColumnAndRow(1, 3, 'Pregunta')
        ->setCellValueByColumnAndRow(2, 3, 'Opciones')
        ->setTitle('Preguntas');
      $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      
      //agrego la hoja con la lista de docentes (segunda hoja)
      $this->_agregarHojaDocente(2,'Docentes',$docentes);
      
      //recorrer secciones del formulario
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $posItem = array(); $secItem = array();
      $cntSecciones = 0; $cntPreguntas = 0;
      foreach ($secciones as $i => $seccion) {
        //agrego la lista de preguntas de la sección a la hoja de listado de preguntas (primera hoja)
        $items = $seccion->listarItemsCarrera($idCarrera);
        $this->_listarPreguntasHoja($this->phpexcel->setActiveSheetIndex(1), $items, $cntPreguntas+4);
        $cntPreguntas += count($items);
        
        //genero un array para obtener posicion y seccion a partir del id de la pregunta
        foreach ($items as $k => $item) {
          $posItem[$item->idPregunta] = $k;
          $secItem[$item->idPregunta] = $i;
        }
        //agrego una hoja por cada sección del formulario
        $this->_agregarHojaSeccion($cntSecciones+3, 'Sección '.($i+1), $seccion, $items);
        $cntSecciones++;
      }

      //obtengo respuestas de la base de datos
      if (count($docentes)>1){$respuestas = $encuesta->respuestasMateria($idCarrera, $idMateria);}
      elseif (count($docentes)==1){$respuestas = $encuesta->respuestasMateriaDocente($idCarrera, $idMateria, $docentes->id);}
      else{$respuestas = $encuesta->respuestasMateriaDocente($idCarrera, $idMateria, 0);}
      $this->_poblarPlanilla($respuestas, $posItem, $secItem, $cntSecciones);
      
      //genero la descarga para el usuario
      if (!$this->_descargaArchivo($tipo,'datos_encuesta')) show_404();
    }
    //si no pasa la validación
    else{
      redirect('informes/materia');
    }
  }

  /*
   * Descargar datos de encuesta para una carrera, en formato de excel
   */
  public function archivoCarrera(){
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('tipo','Tipo de archivo','required|alpha');
    if($this->form_validation->run()){
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idCarrera = (int)$this->input->post('idCarrera');
      $tipo = $this->input->post('tipo');
      
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Usuario');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->library('PHPExcel/PHPExcel');

      //verifico que los datos enviados son correctos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      if (!$encuesta || !$formulario || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', 'Los datos ingresados son incorrectos.');
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
        redirect('informes/carrera');
      }

      //obtengo el departamento al que pertenece la carrera
      $departamento = $this->gd->dame($carrera->idDepartamento);
      
      //verifico si el usuario tiene permisos para la carrera
      if ($carrera->publicarInformes != RESPUESTA_SI){
        if (!$this->ion_auth->logged_in()){
          $this->session->set_flashdata('resultadoOperacion', 'Para descargar la planilla de cálculo primero debe iniciar sesión.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          redirect('informes/carrera');
        }
        if(!($this->ion_auth->in_group(array('admin','decanos')) ||
            ($departamento->idJefeDepartamento == $this->data['usuarioLogin']->id) ||
            ($carrera->idDirectorCarrera == $this->data['usuarioLogin']->id) )){
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para descargar los datos de la carrera. Sólo pueden hacerlo las autoridades correspondientes.');
          $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
          redirect('informes/carrera');
        }
      }
      
      //Iniciar la planilla de cálculo
      $this->phpexcel->getProperties()->setCreator('Sistema Encuestas Vía Web')
                     ->setLastModifiedBy('Sistema Encuestas Vía Web')
                     ->setTitle('Datos Encuestas '.$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setSubject('Datos Encuestas '.$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setDescription('Datos de las encuestas para la carrera '.$carrera->nombre.', plan '.$carrera->plan);
      
      //genero primera pagina con datos generales
      $this->phpexcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, 1, 'DATOS DE LAS ENCUESTAS')
        ->setCellValueByColumnAndRow(0, 3, "Departamento: $departamento->nombre")
        ->setCellValueByColumnAndRow(0, 2, "Carrera: $carrera->nombre, plan $carrera->plan")
        ->setCellValueByColumnAndRow(0, 4, "Período de encuesta: $encuesta->año, ".PERIODO.' '.$encuesta->cuatrimestre)
        ->setCellValueByColumnAndRow(0, 5, 'Fecha de inicio: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaInicio)))
        ->setCellValueByColumnAndRow(0, 6, 'Fecha de cierre: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaFin)))
        ->setCellValueByColumnAndRow(0, 7, "Formulario: $formulario->titulo")
        ->setCellValueByColumnAndRow(0, 9, 'Esta planilla de cálculo contiene los datos de las encuestas, organizados de la siguiente forma:')
        ->setCellValueByColumnAndRow(0, 10, '* La primera hoja contiene los datos generales de la encuesta.')
        ->setCellValueByColumnAndRow(0, 11, '* La segunda hoja contiene el listado de preguntas que conforman el formulario utilizado en la encuesta.')
        ->setCellValueByColumnAndRow(0, 12, '* La tercer hoja contiene el listado de docentes que participaron en la encuesta.')
        ->setCellValueByColumnAndRow(0, 13, 'El resto de las hojas contienes los datos de cada sección del formulario, que tienen el siguiente formato:')
        ->setCellValueByColumnAndRow(0, 14, '* La primer columna contiene el identificador de la clave de acceso que se usó para responder la encuesta.')
        ->setCellValueByColumnAndRow(0, 15, '* La segunda columna contiene el identidicador del docente al cual se refiere la pregunta. Si este valor es vacío significa que la pregunta es referida a la cátedra.')
        ->setCellValueByColumnAndRow(0, 16, '* El resto de las columnas muestran las respuestas dadas para una pregunta en particular. En el encabezado de las columnas se muestra el identidicador de la pregunta.')
        ->setTitle('Información');
      
      //creo la hoja para el listado de preguntas
      $this->phpexcel->createSheet();
      $worksheet = $this->phpexcel->setActiveSheetIndex(1)
        ->setCellValueByColumnAndRow(0, 1, 'LISTADO DE PREGUNTAS')
        ->setCellValueByColumnAndRow(0, 3, 'ID Pregunta')
        ->setCellValueByColumnAndRow(1, 3, 'Pregunta')
        ->setCellValueByColumnAndRow(2, 3, 'Opciones')
        ->setTitle('Preguntas');
      $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
      
      //agrego la hoja con la lista de docentes (segunda hoja)
      $docentes = $encuesta->listarDocentesCarrera($idCarrera);
      $this->_agregarHojaDocente(2,'Docentes',$docentes);
        
      //recorrer secciones del formulario
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $posItem = array(); $secItem = array();
      $cntSecciones = 0; $cntPreguntas = 0;
      foreach ($secciones as $i => $seccion) {
        //agrego la lista de preguntas de la sección a la hoja de listado de preguntas (primera hoja)
        $items = $seccion->listarItemsCarrera($idCarrera);
        $this->_listarPreguntasHoja($this->phpexcel->setActiveSheetIndex(1), $items, $cntPreguntas+4);
        $cntPreguntas += count($items);
        
        //genero un array para obtener posicion y seccion a partir del id de la pregunta
        foreach ($items as $k => $item) {
          $posItem[$item->idPregunta] = $k;
          $secItem[$item->idPregunta] = $i;
        }
        //agrego una hoja por cada sección del formulario
        $this->_agregarHojaSeccion($cntSecciones+3, 'Sección '.($i+1), $seccion, $items);
        $cntSecciones++;
      }

      //obtengo respuestas de la base de datos
      $respuestas = $encuesta->respuestasCarrera($idCarrera);
      $this->_poblarPlanilla($respuestas, $posItem, $secItem, $cntSecciones);
      
      //genero la descarga para el usuario
      if (!$this->_descargaArchivo($tipo,'datos_encuesta')) show_404();
    }
    //si no pasa la validación
    else{
      redirect('informes/carrera');
    }
  }

  /*
   * Insertar el listado de preguntas en una hoja de la planilla de cálculo.
   * -worksheet: hoja de la planilla
   * -items: array de objetos Items a listar
   * -inifila: posicion de la fila donde empezar a listar los items
   */
  private function _listarPreguntasHoja($worksheet, $items, $inifila){
    $fila = $inifila;
    foreach ($items as $item) {      
      //guardo datos de la pregunta
      $worksheet->setCellValueByColumnAndRow(0, $fila, $item->idPregunta)
                ->setCellValueByColumnAndRow(1, $fila, $item->texto);
      $worksheet->getStyleByColumnAndRow(0, $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(0, $fila)->getFill()->getStartColor()->setARGB('FFE1E2FF');
      switch ($item->tipo) {
        case TIPO_SELECCION_SIMPLE:
          $opciones = $item->listarOpciones();
          foreach ($opciones as $j => $opcion) {
            $worksheet->setCellValueByColumnAndRow($j+2, $fila, $opcion->texto.': '.$opcion->idOpcion);
          }
          break;
        case TIPO_NUMERICA:
          $worksheet->setCellValueByColumnAndRow(2, $fila, 'Limite inferior: '.$item->limiteInferior)
                    ->setCellValueByColumnAndRow(3, $fila, 'Limite superior: '.$item->limiteSuperior)
                    ->setCellValueByColumnAndRow(4, $fila, 'Paso: '.$item->paso);
          break;
      }
      $fila++;
    }
  }
  
  /*
   * Crear una hoja en la planilla, colocarle titulo, identificadores de pregunta y docentes
   * -posPagina: posicion de la nueva hoja
   * -nombre: nombre de la hoja
   * -seccion: objeto Sección
   * -items: array de Items a listar
   */
  private function _agregarHojaSeccion($posPagina, $nombre, $seccion, $items){
    $this->phpexcel->createSheet();
    $worksheet = $this->phpexcel->setActiveSheetIndex($posPagina);
    $worksheet->setCellValueByColumnAndRow(0, 1, 'SECCIÓN: '.$seccion->texto)
              ->setCellValueByColumnAndRow(0, 2, 'Descripción: '.$seccion->descripcion)
              ->setCellValueByColumnAndRow(0, 3, 'Clave')
              ->setCellValueByColumnAndRow(1, 3, 'Docente')
              ->setTitle($nombre);
    //le aplico un color de fondo
    $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
    $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
    foreach ($items as $k => $item) {
      //pongo el ID de la pregunta al inicio de cada columna correspondiente
      $worksheet->setCellValueByColumnAndRow($k+2, 3, $item->idPregunta);
      //le aplico un color de fondo
      $worksheet->getStyleByColumnAndRow($k+2, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow($k+2, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');      
    }
  }
  
  /*
   * Crear una hoja en la planilla para colocar listado de docentes
   * -posPagina: posicion de la nueva hoja
   * -nombre: nombre de la hoja
   * -seccion: objeto Sección
   * -items: array de Items a listar
   */
  private function _agregarHojaDocente($posPagina, $nombre, $docentes){
    $this->phpexcel->createSheet();
    $worksheet = $this->phpexcel->setActiveSheetIndex($posPagina);
    $worksheet->setCellValueByColumnAndRow(0, 1, 'LISTADO DE DOCENTES')
              ->setCellValueByColumnAndRow(0, 3, 'Clave')
              ->setCellValueByColumnAndRow(1, 3, 'Apellido')
              ->setCellValueByColumnAndRow(2, 3, 'Nombre')
              ->setTitle($nombre);
    $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyleByColumnAndRow(0, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
    $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyleByColumnAndRow(1, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
    $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyleByColumnAndRow(2, 3)->getFill()->getStartColor()->setARGB('FFC0C0FF');
    foreach ($docentes as $k => $docente) {
      //pongo el ID del docente al inicio de cada columna correspondiente
      $worksheet->setCellValueByColumnAndRow(0, $k+4, $docente->id)
                ->setCellValueByColumnAndRow(1, $k+4, $docente->apellido)
                ->setCellValueByColumnAndRow(2, $k+4, $docente->nombre);
      $worksheet->getStyleByColumnAndRow(0, $k+4)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $worksheet->getStyleByColumnAndRow(0, $k+4)->getFill()->getStartColor()->setARGB('FFE1E2FF');
    }
  }
  
  /*
   * Llenar una planilla de excel con los datos de la base de datos
   * -respuestas: array con datos. Estan ordenados por idDocente y por idClave
   * -posItem: array que me asocia un idItem con la posicion de la columna donde debe ubicarse
   * -secItem: array que me asocia un idItem con la posicion de la seccion a la que pertenece (equivalente al numero de pagina)
   * -cntSecciones: cantidad de secciones (equivalente a cantidad de páginas del libro de excel-2)
   */
  private function _poblarPlanilla($respuestas, $posItem, $secItem, $cntSecciones){
    for($i=0; $i<$cntSecciones; $i++){
      //los datos van a partir de fila 4, clave nula
      $filaPagina[$i] = 3; 
      $clavePagina[$i] = null;
      $docentePagina[$i] = null;  
    }
    foreach ($respuestas as $respuesta) {
      $idPregunta = $respuesta['idPregunta'];
      $idClave = $respuesta['idClave'];
      $idDocente = $respuesta['idDocente'];
      
      //calculo la posicion de la columna y la pagina de la respuesta actual
      $col = $posItem[$idPregunta]; //dos primeras columnas reservadas para los ID
      $seccion = $secItem[$idPregunta]; //la primera pagina reservada para informacion y las preguntas
      
      //si la clave cambia, significa que debo pasar a la siguiente fila de la pagina actual (empiezan respuestas de otro alumno)
      if ($idClave != $clavePagina[$seccion] || $idDocente != $docentePagina[$seccion]){
        $filaPagina[$seccion]++;
        $clavePagina[$seccion] = $idClave;
        $docentePagina[$seccion] = $idDocente;
        
        $this->phpexcel->setActiveSheetIndex($seccion+3)
        ->setCellValueByColumnAndRow(0,      $filaPagina[$seccion], $idClave)
        ->setCellValueByColumnAndRow(1,      $filaPagina[$seccion], $idDocente);
        $this->phpexcel->getActiveSheet()->getStyleByColumnAndRow(0, $filaPagina[$seccion])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->phpexcel->getActiveSheet()->getStyleByColumnAndRow(0, $filaPagina[$seccion])->getFill()->getStartColor()->setARGB('FFE1E2FF');
        $this->phpexcel->getActiveSheet()->getStyleByColumnAndRow(1, $filaPagina[$seccion])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->phpexcel->getActiveSheet()->getStyleByColumnAndRow(1, $filaPagina[$seccion])->getFill()->getStartColor()->setARGB('FFE1E2FF');
      }
      //ubico la respuesta actual dentro de la planilla de cálculo. Es una hoja por sección y por docente. Cada columna tiene las respuestas a una pregunta.
      $this->phpexcel->setActiveSheetIndex($seccion+3)
        ->setCellValueByColumnAndRow($col+2, $filaPagina[$seccion], $respuesta['opcion'].$respuesta['texto']);
    }
  }

  /*
   * Realiza el render del archivo e inicia la descarga.
   * -tipo: tipo de archivo (equivalente a la extension del archivo)
   * -nombre: nombre del archivo a generar.
   */
  private function _descargaArchivo($tipo, $nombre='datos-encuesta'){
    //genero la descarga para el usuario
    switch ($tipo) {
      case 'xls':
        ob_end_clean(); //HACE FALTA A VECES. DEPENDE DEL SERVIDOR.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre.'.xls"');
        header('Cache-Control: max-age=0');
        //genero el archivo y guardo
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean(); //HACE FALTA A VECES. DEPENDE DEL SERVIDOR.
        break;
      case 'xlsx':
        ob_end_clean(); //HACE FALTA A VECES. DEPENDE DEL SERVIDOR.
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
        ob_end_clean(); //HACE FALTA A VECES. DEPENDE DEL SERVIDOR.
        break;
      default:
        return false;
    }
    return true;
  }
}
?>