<?php

/**
 * 
 */
class Encuestas extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de encuestas.
   * Última revisión: 2012-02-06 11:07 p.m.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    //obtengo lista de encuestas
    $lista = $this->ge->listar($pagInicio, self::per_page);
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("encuestas/listar"),
      'total_rows' => $this->ge->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las encuestas
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_encuestas', $this->data);
  }
  
  /*
   * Ver y editar datos relacionados a una encuesta
   * Última revisión: 2012-02-06 11:45 p.m.
   */
  public function ver($idEncuesta=null, $idFormulario=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idEncuesta = (int)$idEncuesta;
    $idFormulario = (int)$idFormulario;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    //obtengo datos de la encuesta
    $this->data['encuesta'] = $this->ge->dame($idEncuesta, $idFormulario);
    if ($this->data['encuesta']){
      $this->load->view('ver_encuesta', $this->data);
    }
    else{
      show_error('El Identificador de Encuesta no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva encuesta
   * POST: idFormulario, anio, cuatrimestre
   * Última revisión: 2012-02-06 11:37 p.m.
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('anio','Año','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
    $this->form_validation->set_rules('cuatrimestre','Periodo/Cuatrimestre','required|is_natural_no_zero|less_than[12]');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_encuestas','ge');
      
      //agrego encuesta y cargo vista para mostrar resultado
      $res = $this->ge->alta($this->input->post('idFormulario',TRUE), $this->input->post('anio',TRUE), $this->input->post('cuatrimestre',TRUE));
      $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $this->data['link'] = site_url("encuestas/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para finalizar una Encuesta
   * POST: idEncuesta, idFormulario
   * Última revisión: 2012-02-06 11:38 p.m.
   */
  public function finalizar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      $this->Encuesta->idEncuesta = $this->input->post('idEncuesta', TRUE); 
      $this->Encuesta->idFormulario = $this->input->post('idFormulario', TRUE);
      
      //finalizo la encuesta y cargo vista para mostrar resultado
      $res = $this->Encuesta->finalizar();
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("encuestas/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Obtener datos de una seccion para mostrar en el informe por materia
   * Última revisión: 2012-02-09 7:32 p.m.
   */
  private function _dameDatosSeccionMateria($seccion, $encuesta, $idDocente, $idMateria, $idCarrera){
      $items = $seccion->listarItemsCarrera();
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
   * Solicitar y mostrar un informe por materia
   * Última revisión: 2012-02-09 7:39 p.m.
   */
  public function informeMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    if($this->form_validation->run() && sscanf($this->input->post('encuesta'),"%d_%d",$idEncuesta,$idFormulario)==2){
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
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Usuario');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_usuarios','gu');

      //obtener la lista de docentes que participa en la encuesta, y el datos del usuario actual
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $listaDocentes = $encuesta->listarDocentes($idMateria, $idCarrera);
      $usuario = $this->gu->dame($this->data['usuarioLogin']->id);
      $datosDocente = $usuario->dameDatosDocente($idMateria);
      //si el usuario no es docente o tiene acceso como jefe de cátedra
      if (!$this->ion_auth->in_group('docentes') || (count($datosDocente)>0 && $datosDocente['tipoAcceso']=='J')){
        //mostrar resultados para todos los docentes
        $docentes = &$listaDocentes;
      }
      else{
        //sino, mostrar solo resultado del usuario logueado
        $docentes = array();
        foreach ($listaDocentes as $docente) {
          if ($docente->id == $usuario->id){$docentes[0] = $docente;break;} 
        }
      }
      //obtener datos de la encuesta
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      $this->Usuario->id = 0; //importante. Es el id de un docente no existente.
      
      //recorrer secciones, docentes, y preguntas obteniendo resultados
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['subsecciones'] = array();
        $datos_secciones[$i]['indice'] = ($indicesSecciones)?$encuesta->indiceSeccionMateria($idMateria, $idCarrera, $seccion->idSeccion):null;
        switch ($seccion->tipo){
        //si la sección es referida a docentes
        case 'D':
          foreach ($docentes as $j => $docente) {
            $datos_secciones[$i]['subsecciones'][$j] = array(
              'docente' => $docente,
              'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, $docente->id, $materia->idMateria, $carrera->idCarrera),
              'indice' => ($indicesDocentes)?$encuesta->indiceDocenteMateria($idMateria, $idCarrera, $seccion->idSeccion, $docente->id):null
            );
          }
          break;
        //si la sección es referida a la materia (sección comun)
        case 'N':
          $datos_secciones[$i]['subsecciones'][0] =  array(
            'docente' => $this->Usuario,
            'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, 0, $materia->idMateria, $carrera->idCarrera),
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
        'claves' => $encuesta->cantidadClavesMateria($idMateria, $idCarrera),
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalMateria($idMateria, $idCarrera):null,
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
   * Última revisión: 2012-02-09 8:17 p.m.
   */
  public function informeCarrera(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores'))){
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
        $items = $seccion->listarItemsCarrera();
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
      $this->load->view('informe_carrera', $datos);
    }
    else{
      $this->load->view('solicitud_informe_carrera', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe por departamento
   * Última revisión: 2012-02-09 8:17 p.m.
   */
  public function informeDepartamento(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos'))){
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
      $this->load->view('informe_departamento', $datos);
    }
    else{
      $this->load->view('solicitud_informe_departamento', $this->data);
    }
  }

  /*
   * Solicitar y mostrar un informe por facultad
   * Última revisión: 2012-02-10 1:42 p.m.
   */
  public function informeFacultad(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group(array('admin','decanos'))){
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
      $this->load->view('informe_facultad', $datos);
    }
    else{
      $this->load->view('solicitud_informe_facultad', $this->data);
    }
  }



  /*
   * Solicitar y mostrar un informe por clave (es decir, por alumno)
   * Última revisión: 2012-02-13 7:09 p.m.
   */
  public function informeClave(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idClave','Clave','required|is_natural_no_zero');
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    if($this->form_validation->run() && sscanf($this->input->post('encuesta'),"%d_%d",$idEncuesta,$idFormulario)==2){
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
      $this->load->model('Usuario');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');
      $this->load->model('Gestor_usuarios','gu');

      //obtener la lista de docentes que participa en la encuesta, y el datos del usuario actual
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $listaDocentes = $encuesta->listarDocentes($idMateria, $idCarrera);
      $usuario = $this->gu->dame($this->data['usuarioLogin']->id);
      $datosDocente = $usuario->dameDatosDocente($idMateria);
      //si el usuario no es docente o tiene acceso como jefe de cátedra
      if (!$this->ion_auth->in_group('docentes') || (count($datosDocente)>0 && $datosDocente['tipoAcceso']=='J')){
        //mostrar resultados para todos los docentes
        $docentes = &$listaDocentes;
      }
      else{
        //sino, mostrar solo resultado del usuario logueado
        $docentes = array();
        foreach ($listaDocentes as $docente) {
          if ($docente->id == $usuario->id){$docentes[0] = $docente;break;} 
        }
      }
      //obtener datos de la encuesta
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $clave = $encuesta->dameClave($idClave, $idMateria, $idCarrera);
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
        case 'D':
          foreach ($docentes as $j => $docente) {
            $items = $seccion->listarItemsCarrera();
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
        case 'N':
          $items = $seccion->listarItemsCarrera();
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
        'indice' => ($indiceGlobal)?$encuesta->indiceGlobalClave($idClave, $idMateria, $idCarrera):null,
        'clave' => &$clave,
        'secciones' => &$datos_secciones
      );
      $this->load->view('informe_clave', $datos);
    }
    else{
      $this->load->view('solicitud_informe_clave', $this->data);
    }
  }

  //funcion para responder solicitudes AJAX
  public function listarClavesAJAX(){
    $idMateria = $this->input->post('idMateria');
    $idCarrera = $this->input->post('idCarrera');
    $idEncuesta = $this->input->post('idEncuesta');
    $idFormulario = $this->input->post('idFormulario');
    //VERIFICAR
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->Encuesta->idEncuesta = $idEncuesta;
    $this->Encuesta->idFormulario = $idFormulario;
    $claves = $this->Encuesta->listarClavesMateria($idMateria, $idCarrera, 0,1000);
    foreach ($claves as $clave) {
      echo  "$clave->idClave\t".
            "$clave->clave\t".
            "$clave->tipo\t".
            "$clave->generada\t".
            "$clave->utilizada\t\n";
    }
  }

  //funcion para responder solicitudes AJAX
  public function buscarEncuestaAJAX(){
    $año = $this->input->post('buscar');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    $encuestas = $this->ge->buscar($año);
    echo "\n";
    foreach ($encuestas as $encuesta) {
      echo  "$encuesta->idEncuesta\t".
            "$encuesta->idFormulario\t".
            "$encuesta->año\t".
            "$encuesta->cuatrimestre\t".
            "$encuesta->fechaInicio\t\n";
    }
  }









/*
 * public function ver($idEncuesta=null, $idFormulario=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idEncuesta = (int)$idEncuesta;
    $idFormulario = (int)$idFormulario;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    //obtengo datos de la encuesta
    $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
    if ($encuesta){
      $cantidadClaves = 0;//$encuesta->cantidadClaves();
      $claves = array();//$encuesta->listarClaves($pagInicio, 5);
      $data['encuesta'] = array(
        'IdEncuesta' => $encuesta->IdEncuesta,
        'idFormulario' => $encuesta->idFormulario,
        'Año' => $encuesta->Año,
        'Cuatrimestre' => $encuesta->Cuatrimestre,
        'FechaInicio' => $encuesta->FechaInicio,
        'FechaFin' => $encuesta->FechaFin
      );
      //genero la lista de links de paginación
      $config['base_url'] = site_url("encuestas/ver/$idEncuesta/$idFormulario");
      $config['total_rows'] = $cantidadClaves;
      $config['per_page'] = 5;
      $config['uri_segment'] = 5;
      $this->pagination->initialize($config);
      //obtengo lista de claves
      $tabla = array();
      foreach ($claves as $i => $clave) {
        $tabla[$i]=array(
          'IdClave' => $clave->IdClave,
          'Clave' => $clave->Clave,
          'Tipo' => $clave->Tipo,
          'Generada' => $clave->Generada,
          'Utilizada' => $clave->Utilizada
         );
      }
      //envio datos a la vista
      $data['tabla'] = $tabla; //array de datos de los Departamentos
      $data['encuesta'] = array(
        'IdEncuesta' => $encuesta->IdEncuesta, 
        'idFormulario' => $encuesta->idFormulario,
        'Año' => $encuesta->Año,  
        'Cuatrimestre' => $encuesta->Cuatrimestre,
        'FechaInicio' => $encuesta->FechaInicio,
        'FechaFin' => $encuesta->FechaFin
      );
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('ver_encuesta', $data);
    }
    else{
      show_error('El Identificador de Encuesta no es válido.');
    }
  }
 */

 
 public function tmp(){
   $this->load->view('tmp');
 }
  
}

?>