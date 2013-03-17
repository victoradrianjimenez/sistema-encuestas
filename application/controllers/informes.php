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
    $this->data['resultadoTipo'] = ALERT_ERROR;
    $this->data['resultadoOperacion'] = null;
  }

  private function _filtrarListaDocentes($materia, $listaDocentes){
    $this->Usuario->id = ($this->data['usuarioLogin']) ? $this->data['usuarioLogin']->id : null;
    $datosDocente = $this->Usuario->dameDatosDocente($materia->idMateria);
    if ($materia->publicarInformes == "S"){
      return $listaDocentes; //mostrar resultados para todos los docentes
    }
    elseif ($this->ion_auth->in_group('admin','decanos','jefes_departamentos','directores') || (isset($datosDocente['tipoAcceso']) && $datosDocente['tipoAcceso']==TIPO_ACCESO_JEFE_CATEDRA)){
      //si el usuario es una autoridad o tiene acceso como jefe de cátedra
      return $listaDocentes; //mostrar resultados para todos los docentes
    }
    else{
      //sino, mostrar solo resultado del usuario logueado
      $docentes = array();
      if ($this->data['usuarioLogin']){
        //si hay un usuario logueado, buscar entre los docentes de la encuesta
        foreach ($listaDocentes as $docente) {
          if ($docente->id == $this->data['usuarioLogin']->id){$docentes[0] = $docente; break;} 
        }
      }
      return $docentes;
    }
  }

  private function _verificarPermisosMateria($materia){
    $this->Usuario->id = ($this->data['usuarioLogin']) ? $this->data['usuarioLogin']->id : null;
    //verifico si el usuario tiene permisos para continuar
    if ($materia->publicarInformes != "S"){
      //si la materia no tiene informes como públicos
      if ($this->ion_auth->logged_in() && $this->Usuario->id){
        //si el usuario esta logueado
        if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores', 'docentes'))){
          //si el usuario no es una autoridad
          if (!$this->ion_auth->in_group('docentes')){
            //si el usuario no es un docente
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por materia.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
          elseif(count($this->Usuario->dameDatosDocente($idMateria)) == 0){
            //si el usuario es un docente, pero no de la materia que se quiere ver un informe
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes de esta asignatura.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
        }
      }
      else {
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        return false;
      }
    }
    return true;
  }

  private function _verificarPermisosCarrera($carrera){
    //verifico si el usuario tiene permisos para continuar
    if ($carrera->publicarInformes != "S"){
      //si la carrera no tiene informes como públicos
      if ($this->ion_auth->logged_in() && $this->data['usuarioLogin']){
        //si el usuario esta logueado
        if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores'))){
          //si el usuario no es una autoridad
          if (!$this->ion_auth->in_group('directores')){
            //si el usuario no es un director
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por carrera.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
          elseif($carrera->idDirectorCarrera != $this->data['usuarioLogin']->id){
            //si el usuario es un director, pero no de la carrera que se quiere ver un informe
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes de esta carrera.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
        }
      }
      else {
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        return false;
      }
    }
    return true;
  }
  
  private function _verificarPermisosDepartamento($departamento){
    if ($departamento->publicarInformes != "S"){
      //si el departamento no tiene informes como públicos
      if ($this->ion_auth->logged_in() && $this->data['usuarioLogin']){
        //si el usuario esta logueado
        if (!$this->ion_auth->in_group(array('admin','decanos'))){
          //si el usuario no es una autoridad superior
          if (!$this->ion_auth->in_group('jefes_departamentos')){
            //si el usuario no es un jefe de departamento
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por departamento.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
          elseif($departamento->idJefeDepartamento != $this->data['usuarioLogin']->id){
            //si el usuario es un jefe de departamento, pero no del departamento que se quiere ver un informe
            $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes de esta departamento.');
            $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
            return false;
          }
        }
      }
      else {
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        return false;
      }
    }
    return true;
  }

  private function _verificarPermisosFacultad(){
    //verifico si el usuario tiene permisos para continuar
    if ( $this->config->item('publicarInformes', 'facultad') ){
      //si la facultad tiene informes como públicos
      if ($this->ion_auth->logged_in()){
        //si el usuario esta logueado
        if (!$this->ion_auth->in_group(array('admin','decanos'))){
          //si el usuario no es una autoridad superior
          $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para ver informes por facultad.');
          $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
          return false;
        }
      }
      else {
        $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
        return false;
      }
    }
    return true;
  }
    
    
  /*
   * Obtener datos de una seccion para mostrar en el informe por materia (funcion auxiliar)
   */
  private function _dameDatosSeccionMateria($seccion, $encuesta, $idDocente, $idMateria, $idCarrera){
      $items = $seccion->listarItemsCarrera($idCarrera);
      $datos_items = array();
      foreach ($items as $k => $item) {
        switch ($item->tipo) {
        case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
          $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->idPregunta, $idDocente, $idMateria, $idCarrera);
          break;
        case TIPO_TEXTO_SIMPLE: case TIPO_TEXTO_MULTILINEA:
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

      //obtener la lista de docentes que participa en la encuesta, y el datos del usuario actual
      $listaDocentes = $encuesta->listarDocentes($idMateria, $idCarrera);

      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosMateria($materia)) redirect('informes/materia');
      
      //obtengo lista de docentes, que depende de que permisos tenga el usuario logueado
      $docentes = $this->_filtrarListaDocentes($materia, $listaDocentes);
      
      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $this->Usuario->id = 0; //importante. Es el id de un docente no existente.
      
      //recorrer secciones, docentes, y preguntas obteniendo resultados
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i] = array(
          'seccion' => $seccion,
          'subsecciones' => array(),
          'indice' => ($indicesSecciones)?$encuesta->indiceSeccionMateria($idMateria, $idCarrera, $seccion->idSeccion):null
        );
        switch ($seccion->tipo){
        case SECCION_TIPO_DOCENTE:
          foreach ($docentes as $j => $docente) {
            $datos_secciones[$i]['subsecciones'][$j] = array(
              'docente' => $docente,
              'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, $docente->id, $idMateria, $carrera->idCarrera),
              'indice' => ($indicesDocentes)?$encuesta->indiceDocenteMateria($idMateria, $idCarrera, $seccion->idSeccion, $docente->id):null
            );
          }
          break;
        //si la sección es referida a la materia (sección comun)
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
        'departamento' => $this->gd->dame($carrera->idDepartamento),
        'materia' => &$materia,
        'claves' => $encuesta->cantidadClavesMateria($idMateria, $idCarrera),
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalMateria($idMateria, $idCarrera):null,
        'graficos' => &$graficos,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_materia', $datos);
    }
    else{
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
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

      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosCarrera($carrera)) redirect('informes/carrera');
      
      //obtener datos del formulario usado en la encuesta
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i] =  array(
          'seccion' => $seccion,
          'items' => array(),
          'indice' => ($indicesSecciones)?$encuesta->indiceSeccionCarrera($idCarrera, $seccion->idSeccion):null
        );        
        $items = $seccion->listarItemsCarrera($idCarrera);
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
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
        'departamento' => $this->gd->dame($carrera->idDepartamento),
        'claves' => $encuesta->cantidadClavesCarrera($idCarrera),
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalCarrera($idCarrera):null,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_carrera', $datos);
    }
    else{
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
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

      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosDepartamento($departamento)) redirect('informes/departamento');

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
      $this->load->view('informe_departamento', $datos);
    }
    else{
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
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
      
      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosFacultad()) redirect('informes/facultad');;

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
      $this->load->view('informe_facultad', $datos);
    }
    else{
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
      $this->load->view('solicitud_informe_facultad', $this->data);
    }
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

      //obtener la lista de docentes que participa en la encuesta, y el datos del usuario actual
      $listaDocentes = $encuesta->listarDocentes($idMateria, $idCarrera);

      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosMateria($materia)) redirect('informes/clave');

      //obtengo lista de docentes, que depende de que permisos tenga el usuario logueado
      $docentes = $this->_filtrarListaDocentes($materia, $listaDocentes);
      
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
        'departamento' => $this->gd->dame($carrera->idDepartamento),
        'materia' => &$materia,
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalClave($idClave, $idMateria, $idCarrera):null,
        'clave' => &$clave,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_clave', $datos);
    }
    else{
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
      $this->load->view('solicitud_informe_clave', $this->data);
    }
  }


  /*
   * Solicitar y mostrar un informe por facultad
   */
  public function archivoMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
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
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_encuestas','ge');
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
      
      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosMateria($materia)) redirect('informes/materia');
      
      //obtengo lista de docentes, que depende de que permisos tenga el usuario logueado
      $listaDocentes = $encuesta->listarDocentes($idMateria, $idCarrera);
      $docentes = $this->_filtrarListaDocentes($materia, $listaDocentes);
      
      //obtengo datos de los docentes             
      $posDocente = array();
      foreach ($docentes as $i => $docente) {
        //genero un array para obtener posicion a partir del id
        $posDocente[$docente->id] = $i;
      }
      
      //Iniciar la planilla de cálculo
      $this->phpexcel->getProperties()->setCreator("Sistema Encuestas Vía Web")
                     ->setLastModifiedBy("Sistema Encuestas Vía Web")
                     ->setTitle("Datos Encuestas ".$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setSubject("Datos Encuestas ".$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setDescription("Datos de las encuestas para una materia.");
      $this->phpexcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, 1, 'ID Pregunta')
        ->setCellValueByColumnAndRow(1, 1, 'Pregunta')
        ->setCellValueByColumnAndRow(2, 1, 'Opciones')
        ->setTitle('Preguntas');
        
      //recorrer secciones del formulario
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $posItem = array();
      $secItem = array();
      $cntPaginas = 0;
      $cntPreguntas = 0;
      foreach ($secciones as $i => $seccion) {
        //activo la primer pagina de la planilla de cálculo
        $worksheet = $this->phpexcel->setActiveSheetIndex(0);
        
        //listo las preguntas de la sección
        $items = $seccion->listarItemsCarrera($idCarrera);
        $cntPreguntas = $cntPreguntas + $this->_listarPreguntasHoja($worksheet, $items, 2+$cntPreguntas);
        foreach ($items as $k => $item) {
          //genero un array para obtener posicion y seccion a partir del id
          $posItem[$item->idPregunta] = $k;
          $secItem[$item->idPregunta] = $i;
        }
        
        //creo una hoja de excel por cada seccion y por cada docente
        if ($seccion->tipo==SECCION_TIPO_DOCENTE){
          for($j=0; $j < count($docentes); $j++){
            //crear hoja, colocarle titulo e identificadores de pregunta
            $this->_agregarHoja($cntPaginas+1, 'Sección '.($i+1).' Docente '.($j+1), $seccion->texto.' - '.$docentes[$j]->nombre.' '.$docentes[$j]->apellido, $items);
            $cntPaginas++;
          } 
        }
        else{
          $this->_agregarHoja($cntPaginas+1, 'Sección '.($i+1), $seccion->texto, $items);
          $cntPaginas++;
        }
      }

      //obtengo respuestas de la base de datos
      $respuestas = $encuesta->respuestasMateria($idCarrera, $idMateria);
      for($i=0; $i<=$cntPaginas; $i++){
        //los datos van a partir de fila 4, clave nula
        $filaPagina[$i] = 4; 
        $clavePagina[$i] = null; 
      }
      foreach ($respuestas as $respuesta) {
        $idPregunta = $respuesta['idPregunta'];
        $idDocente = $respuesta['idDocente'];
        
        //calculo la posicion de la columna y la pagina de la respuesta actual
        $col = $posItem[$idPregunta] + 1;
        $pagina =  $secItem[$idPregunta] + (($idDocente)?$posDocente[$idDocente]:0) + 1;
        
        //ubico la respuesta actual dentro de la planilla de cálculo. 
        //Es una hoja por sección y por docente. Cada columna tiene las respuestas a una pregunta.
        $this->phpexcel->setActiveSheetIndex($pagina)
          ->setCellValueByColumnAndRow(0,    $filaPagina[$pagina], $respuesta['idClave'])
          ->setCellValueByColumnAndRow($col, $filaPagina[$pagina], $respuesta['opcion'].$respuesta['texto']);
        
        //si la clave cambia, significa que debo pasar a la siguiente fila de la pagina actual (empiezan respuestas de otro alumno)
        if ($respuesta['idClave'] != $clavePagina[$pagina]){
          if ($clavePagina[$pagina] != null) $filaPagina[$pagina]++;
          $clavePagina[$pagina] = $respuesta['idClave']; 
        }
      }

      //genero la descarga para el usuario
      if (!$this->_descargaArchivo($tipo)) show_404();
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
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
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
      $this->load->model('Gestor_carreras','gc');
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
        redirect('informes/materia');
      }
      
      //verifico si el usuario tiene permisos para continuar
      if (!$this->_verificarPermisosCarrera($carrera)) redirect('informes/carrera');
      
      //Iniciar la planilla de cálculo
      $this->phpexcel->getProperties()->setCreator("Sistema Encuestas Vía Web")
                     ->setLastModifiedBy("Sistema Encuestas Vía Web")
                     ->setTitle("Datos Encuestas ".$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setSubject("Datos Encuestas ".$encuesta->año.'/'.$encuesta->cuatrimestre)
                     ->setDescription("Datos de las encuestas para una materia.");
      $this->phpexcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, 1, 'ID Pregunta')
        ->setCellValueByColumnAndRow(1, 1, 'Pregunta')
        ->setCellValueByColumnAndRow(2, 1, 'Opciones')
        ->setTitle('Preguntas');
        
      //recorrer secciones del formulario
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $posItem = array();
      $secItem = array();
      $cntPaginas = 0;
      $cntPreguntas = 0;
      foreach ($secciones as $i => $seccion) {
        //activo la primer pagina de la planilla de cálculo
        $worksheet = $this->phpexcel->setActiveSheetIndex(0);
        
        //listo las preguntas de la sección
        $items = $seccion->listarItemsCarrera($idCarrera);
        $cntPreguntas = $cntPreguntas + $this->_listarPreguntasHoja($worksheet, $items, 2+$cntPreguntas);
        foreach ($items as $k => $item) {
          //genero un array para obtener posicion y seccion a partir del id
          $posItem[$item->idPregunta] = $k;
          $secItem[$item->idPregunta] = $i;
        }
        $this->_agregarHoja($cntPaginas+1, 'Sección '.($i+1), $seccion->texto, $items);
        $cntPaginas++;
      }

      //obtengo respuestas de la base de datos
      $respuestas = $encuesta->respuestasCarrera($idCarrera, $idMateria);
      $this->_poblarPlanilla($respuestas, $posItem, $secItem, $posDocente, $cntPaginas);
      
      //genero la descarga para el usuario
      if (!$this->_descargaArchivo($tipo)) show_404();
    }
    //si no pasa la validación
    else{
      redirect('informes/materia');
    }
  }



  private function _listarPreguntasHoja($worksheet, $items, $fila){
    $cnt = 0;
    foreach ($items as $k => $item) {      
      //guardo datos de la pregunta en la primer pagina, a partir de la fila 2
      $opciones = $item->listarOpciones();
      $worksheet->setCellValueByColumnAndRow(0, $fila+$cnt, $item->idPregunta)
                ->setCellValueByColumnAndRow(1, $fila+$cnt, $item->texto);
      foreach ($opciones as $j => $opcion) {
        $worksheet->setCellValueByColumnAndRow(2+$j, $fila+$cnt, $opcion->texto.': '.$opcion->idOpcion);
      }
      $cnt++;
    }
    return $cnt;
  }

  //crear hoja, colocarle titulo e identificadores de pregunta
  private function _agregarHoja($posPagina, $titulo, $descripcion, $items){
    $this->phpexcel->createSheet();
    $worksheet = $this->phpexcel->setActiveSheetIndex($posPagina);
    $worksheet->setTitle($titulo);
    $worksheet->setCellValueByColumnAndRow(0, 1, $descripcion); //titulo de la hoja (celda A1)
    $worksheet->setCellValueByColumnAndRow(0, 3, 'Clave');
    foreach ($items as $k => $item) {
      //pongo el ID de la pregunta al inicio de cada columna correspondiente
      $worksheet->setCellValueByColumnAndRow($k+1, 3, $item->idPregunta);
    }
  }
  
  private function _poblarPlanilla($respuestas, $posItem, $secItem, $posDocente, $cntPaginas){
    for($i=0; $i<=$cntPaginas; $i++){
      //los datos van a partir de fila 4, clave nula
      $filaPagina[$i] = 4; 
      $clavePagina[$i] = null;
      $docentePagina[$i] = null;  
    }
    foreach ($respuestas as $respuesta) {
      $idPregunta = $respuesta['idPregunta'];
      $idDocente = $respuesta['idDocente'];
      
      //calculo la posicion de la columna y la pagina de la respuesta actual
      $col = $posItem[$idPregunta] + 2; //dos primeras columnas reservadas para los ID
      $pagina =  $secItem[$idPregunta] + 1;
      
      //ubico la respuesta actual dentro de la planilla de cálculo. 
      //Es una hoja por sección y por docente. Cada columna tiene las respuestas a una pregunta.
      $this->phpexcel->setActiveSheetIndex($pagina)
        ->setCellValueByColumnAndRow(0,    $filaPagina[$pagina], $respuesta['idClave'])
        ->setCellValueByColumnAndRow(1,    $filaPagina[$pagina], $respuesta['idDocente'])
        ->setCellValueByColumnAndRow($col, $filaPagina[$pagina], $respuesta['opcion'].$respuesta['texto']);
      
      //si la clave cambia, significa que debo pasar a la siguiente fila de la pagina actual (empiezan respuestas de otro alumno)
      if ($respuesta['idClave'] != $clavePagina[$pagina] || $respuesta['idDocente'] != $docentePagina[$pagina]){
        if ($clavePagina[$pagina] != null && $docentePagina[$pagina] != null) {
          $filaPagina[$pagina]++;
        }
        $clavePagina[$pagina] = $respuesta['idClave'];
        $docentePagina[$pagina] = $respuesta['idDocente'];  
      }
    }
  }

  private function _descargaArchivo($tipo){
    //genero la descarga para el usuario
    switch ($tipo) {
      case 'xls':
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="myfile.xls"');
        header('Cache-Control: max-age=0');
        //genero el archivo y guardo
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();
        break;
      case 'xlsx':
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
        ob_end_clean();
        break;
      default:
        return false;
    }
    return true;
}



}
?>