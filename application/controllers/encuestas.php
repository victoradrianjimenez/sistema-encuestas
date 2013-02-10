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
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    $tmp = $this->input->post('encuesta');
    if($this->form_validation->run() && sscanf($tmp, "%d_%d",$idEncuesta, $idFormulario) == 2){
      $idMateria = $this->input->post('idMateria');
      $idCarrera = $this->input->post('idCarrera');
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
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_departamentos','gd');
  
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $materia = $this->gm->dame($idMateria);
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $docentes = $encuesta->listarDocentes($idMateria, $idCarrera);
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_subsecciones = array();
        switch ($seccion->tipo){
        //si la sección es referida a docentes
        case 'D':
          foreach ($docentes as $j => $docente) {
            $datos_subsecciones[$j] = array(
              'docente' => $docente,
              'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, $docente->id, $materia->idMateria, $carrera->idCarrera)
            );
          }
          break;
        //si la sección es referida a la materia (sección comun)
        case 'N':
          $this->Usuario->id = 0;
          $datos_subsecciones[0] =  array(
            'docente' => $this->Usuario,
            'preguntas' => $this->_dameDatosSeccionMateria($seccion, $encuesta, 0, $materia->idMateria, $carrera->idCarrera)
          );
          break;
        }
        $datos_secciones[$i] = array(
          'seccion' => $seccion,
          'subsecciones' => $datos_subsecciones
        );
      }
      $datos['encuesta'] = &$encuesta;
      $datos['formulario'] = &$formulario;
      $datos['carrera'] = &$carrera;
      $datos['departamento'] = &$departamento;
      $datos['materia'] = &$materia;
      $datos['claves'] = $encuesta->cantidadClavesMateria($idMateria, $idCarrera);
      $datos['secciones'] = &$datos_secciones;
      $this->load->view('informe_materia', $datos);
    }
    else{
      $this->load->view('solicitud_informe_materia', $this->data);
    }
  }

  /*
   * Obtener datos de una seccion para mostrar en el informe por carrera
   * Última revisión: 2012-02-09 8:15 p.m.
   */
  private function _dameDatosSeccionCarrera($seccion, $encuesta, $idCarrera){
      
  }

  /*
   * Solicitar y mostrar un informe por carrera
   * Última revisión: 2012-02-09 8:17 p.m.
   */
  public function informeCarrera(){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    $tmp = $this->input->post('encuesta');
    if($this->form_validation->run() && sscanf($tmp, "%d_%d",$idEncuesta, $idFormulario) == 2){
      $idCarrera = $this->input->post('idCarrera');
      //cargo librerias y modelos
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Item');
      $this->load->model('Seccion');
      $this->load->model('Carrera');
      $this->load->model('Formulario');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_formularios','gf');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_encuestas','ge');
  
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $carrera = $this->gc->dame($idCarrera);
      $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $items = $seccion->listarItemsCarrera();
        $datos_items = array();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_respuestas = $encuesta->respuestasPreguntaCarrera($item->idPregunta, $idCarrera);
            break;
          default:
            $datos_respuestas = null;
            break;
          }
          $datos_items[$k] = array(
            'item' => $item,
            'respuestas' => $datos_respuestas
          );
        }
        $datos_secciones[$i] = array(
          'seccion' => $seccion,
          'subsecciones' => $datos_items
        );
      }
      $datos['encuesta'] = $encuesta;
      $datos['formulario'] = $formulario;
      $datos['carrera'] = $carrera;
      $datos['claves'] = $encuesta->cantidadClavesCarrera($idCarrera);
      $datos['secciones'] = $datos_secciones;
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
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','required|is_natural_no_zero');
    $this->form_validation->set_rules('encuesta','Encuesta','required|alpha_dash');
    $tmp = $this->input->post('encuesta');
    if($this->form_validation->run() && sscanf($tmp, "%d_%d",$idEncuesta, $idFormulario) == 2){
      $idDepartamento = $this->input->post('idDepartamento');
      
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
  
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $formulario = $this->gf->dame($idFormulario);
      $departamento= $this->gd->dame($idDepartamento);
      
      $secciones = $formulario->listarSecciones();
      
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $items = $seccion->listarItems();
        $datos_items = array();
        foreach ($items as $k => $item) {
          switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_respuestas = $encuesta->respuestasPreguntaDepartamento($item->idPregunta, $idDepartamento);
            break;
          default:
            $datos_respuestas = null;
            break;
          }
          $datos_items[$k] = array(
            'item' => $item,
            'respuestas' => $datos_respuestas
          );
        }
        $datos_secciones[$i] = array(
          'seccion' => $seccion,
          'items' => $datos_items
        );
      }
      $datos['encuesta'] = &$encuesta;
      $datos['formulario'] = &$formulario;
      $datos['departamento'] = &$departamento;
      $datos['claves'] = $encuesta->cantidadClavesDepartamento($idDepartamento);
      $datos['secciones'] = &$datos_secciones;
      $this->load->view('informe_departamento', $datos);
    }
    else{
      $this->load->view('solicitud_informe_departamento', $this->data);
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