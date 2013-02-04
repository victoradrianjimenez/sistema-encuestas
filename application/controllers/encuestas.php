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
    //datos de session para enviarse a las vistas
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row(); 
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); 
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de encuestas.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    //obtengo lista de encuestas
    $encuestas = $this->ge->listar($pagInicio, self::per_page);
    $tabla = array();
    foreach ($encuestas as $i => $encuesta) {
      $tabla[$i]=array(
        'idEncuesta' => $encuesta->idEncuesta,
        'idFormulario' => $encuesta->idFormulario,
        'año' => $encuesta->año,
        'cuatrimestre' => $encuesta->cuatrimestre,
        'fechaInicio' => $encuesta->fechaInicio,
        'fechaFin' => $encuesta->fechaFin
       );
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("encuestas/listar");
    $config['total_rows'] = $this->ge->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $this->data['tabla'] = &$tabla; //array de datos de los Departamentos
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_encuestas', $this->data);
  }
  
  /*
   * Ver y editar datos relacionados a una encuesta
   */
  public function ver($idEncuesta=null, $idFormulario=null, $pagInicio=0){
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
      $this->data['encuesta'] = array(
        'idEncuesta' => $encuesta->idEncuesta,
        'idFormulario' => $encuesta->idFormulario,
        'año' => $encuesta->año,
        'cuatrimestre' => $encuesta->cuatrimestre,
        'fechaInicio' => $encuesta->fechaInicio,
        'fechaFin' => $encuesta->fechaFin
      );
      $this->load->view('ver_encuesta', $this->data);
    }
    else{
      show_error('El Identificador de Encuesta no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva encuesta
   * POST: idFormulario, Anio, Cuatrimestre
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
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
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $data['link'] = site_url("encuestas/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para finalizar una Encuesta
   * POST: IdEncuesta, idFormulario
   */
  public function finalizar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
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
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("encuestas/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }






  public function informeMateria(){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}

    
    $this->load->model('Materia');
    $this->load->model('Gestor_materias', 'gm');
    
    if ($this->ion_auth->in_group(array('admin','decanos'))){
      $this->data['materias'] = $this->gm->listar(0, 1000);
      echo 'sfdf';
    }
    elseif (!$this->ion_auth->in_group('jefes_departamentos')){
      $this->data['materias'] = $this->gm->listarMateriasJefeDepartamento($this->data['usuarioLogin']->id);
    }
    elseif (!$this->ion_auth->in_group('directores')){
      $this->data['materias'] = $this->gm->listarMateriasDirector($this->data['usuarioLogin']->id);
    }
    elseif (!$this->ion_auth->in_group(array('jefes_catedras','docente'))){
      $this->data['materias'] = $this->gm->listarMateriasDocente($this->data['usuarioLogin']->id);
      
    }
    
    
    
    
    
    
    $this->load->view('solicitud_informe_materia', $this->data);
    
    
    
   // _generarInformeMateria();
  }

















  private function _dameDatosSeccion($seccion, $idDocente, $encuesta, $materia, $carrera){
      $items = $seccion->listarItems();
      $datos_items = array();
      foreach ($items as $k => $item) {
        switch ($item->tipo) {
          case 'S': case 'M': case 'N':
            $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->idPregunta, $idDocente, $materia->idMateria, $carrera->idCarrera);
            break;
          case 'T': case 'X':
            $datos_respuestas = $encuesta->textosPreguntaMateria($item->idPregunta, $materia->idMateria, $carrera->idCarrera);
          default:
            break;
        }
        $datos_items[$k] = array(
          'idPregunta' => $item->idPregunta,
          'texto' => $item->texto,
          'tipo' => $item->tipo,
          'respuestas' => $datos_respuestas
        );
      }
      return $datos_items;
  }

  public function _generarInformeMateria($idMateria, $idCarrera, $idEncuesta, $idFormulario){
    
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Usuario');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_formularios','gf');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');

    $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
    $formulario = $this->gf->dame($idFormulario);
    $carrera = $this->gc->dame($idCarrera);
    $materia = $this->gm->dame($idMateria);
    
    $docentes = $encuesta->listarDocentes($idMateria, $idCarrera);
    $secciones = $formulario->listarSeccionesCarrera($idCarrera);
    
    $datos_secciones = array(); 
    foreach ($secciones as $i => $seccion) {
      $datos_subsecciones = array();
      //si la sección es referida a docentes
      if ($seccion->iipo == 'D'){
        foreach ($docentes as $j => $docente) {
          $datos_subsecciones[$j] = array(
            'id' => $docente->id,
            'apellido' => $docente->apellido,
            'nombre' => $docente->nombre,
            'preguntas' => $this->_dameDatosSeccion($seccion, $docente->id, $encuesta, $materia, $carrera)
          );
        }
      }
      //si la sección es referida a la materia (sección comun)
      else{
        $datos_subsecciones[0] =  array(
          'id' => 0,
          'apellido' => '',
          'nombre' => '',
          'preguntas' => $this->_dameDatosSeccion($seccion, 0, $encuesta, $materia, $carrera)
        );
      }
      $datos_secciones[$i] = array(
        'texto' => $seccion->texto,
        'subsecciones' => $datos_subsecciones
      );
    }
    $datos['encuesta'] = array(
      'año' => $encuesta->año,
      'cuatrimestre' => $encuesta->cuatrimestre,
      'fechaInicio' => $encuesta->fechaInicio,
      'fechaFin' => $encuesta->fechaFin);
    $datos['formulario'] = array(
      'titulo' => $formulario->titulo,
      'descripcion' => $formulario->descripcion);
    $datos['carrera'] = array(
      'nombre' => $carrera->nombre);
    $datos['materia'] = array(
      'nombre' => $materia->nombre);
    $datos['claves'] = $encuesta->cantidadClavesMateria($idMateria, $idCarrera);
    $datos['secciones'] = $datos_secciones;
    $this->load->view('informe_materia', $datos);
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

  
}

?>