<?php

/**
 * 
 */
class Encuestas extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function index(){
    $this->listar();
  }
  
 
  public function listar($pagInicio=0){
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    $cantidadEncuestas = $this->ge->cantidad();
    $encuestas = $this->ge->listar($pagInicio, 5);

    //genero la lista de links de paginación
    $config['base_url'] = site_url("encuestas/listar");
    $config['total_rows'] = $cantidadEncuestas;
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de encuestas
    $tabla = array();
    foreach ($encuestas as $i => $encuesta) {
      $tabla[$i]=array(
        'IdEncuesta' => $encuesta->IdEncuesta,
        'IdFormulario' => $encuesta->IdFormulario,
        'Año' => $encuesta->Año,
        'Cuatrimestre' => $encuesta->Cuatrimestre,
        'FechaInicio' => $encuesta->FechaInicio,
        'FechaFin' => $encuesta->FechaFin
       );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('lista_encuestas', $data);
  }
  

  /*
   * Ver todo lo relacionado a una encuesta
   */
  public function ver($idEncuesta=0, $idFormulario=0, $pagInicio=0){
    if (!is_numeric($idEncuesta) || $idEncuesta<1 || !is_numeric($idFormulario) || $idFormulario<1){
      show_error('El Identificador de Encuesta no es válido.');
      return;
    }
    elseif (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
    if ($encuesta != FALSE){
      $cantidadClaves = 0; //$encuesta->cantidadClaves();
      $claves = array();//$encuesta->listarClaves($pagInicio, 5);
      $data['encuesta'] = array(
        'IdEncuesta' => $encuesta->IdEncuesta,
        'IdFormulario' => $encuesta->IdFormulario,
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
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('ver_encuesta', $data);
    }
    else{
      show_error('El Identificador de Encuesta no es válido.');
    }
  }

  /*
   * Agregar nueva encuesta
   * POST: IdFormulario, Anio, Cuatrimestre
   */
  public function nueva(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $this->form_validation->set_rules('IdFormulario','ID Formulario','required|is_natural_no_zero');
    $this->form_validation->set_rules('Anio','Año','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
    $this->form_validation->set_rules('Cuatrimestre','Periodo/Cuatrimestre','required|is_natural_no_zero|less_than[12]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_encuestas','ge');
      //agrego encuesta y cargo vista para mostrar resultado
      $res = $this->ge->alta($this->input->post('IdFormulario',TRUE), $this->input->post('Anio',TRUE), $this->input->post('Cuatrimestre',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
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
   * Finalizar una Encuesta
   * POST: IdEncuesta, IdFormulario
   */
  public function finalizar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $this->form_validation->set_rules('IdEncuesta','ID Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdFormulario','ID Formulario','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Encuesta');
      $this->Encuesta->IdEncuesta = $this->input->post('IdEncuesta', TRUE); 
      $this->Encuesta->IdFormulario = $this->input->post('IdFormulario', TRUE);
      //finalizo la encuesta y cargo vista para mostrar resultado
      $res = $this->Encuesta->finalizar();
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("encuestas/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }


  private function _dameDatosSeccion($seccion, $IdDocente, $encuesta, $materia, $carrera){
      $items = $seccion->listarItems();
      $datos_items = array();
      foreach ($items as $k => $item) {
        switch ($item->Tipo) {
          case 'S': case 'M': case 'N':
            $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->IdPregunta, $IdDocente, $materia->IdMateria, $carrera->IdCarrera);
            break;
          case 'T': case 'X':
            $datos_respuestas = $encuesta->textosPreguntaMateria($item->IdPregunta, $materia->IdMateria, $carrera->IdCarrera);
          default:
            break;
        }
        $datos_items[$k] = array(
          'IdPregunta' => $item->IdPregunta,
          'Texto' => $item->Texto,
          'Tipo' => $item->Tipo,
          'Respuestas' => $datos_respuestas
        );
      }
      return $datos_items;
  }

  public function informeMateria(){
   
    $IdMateria = 5;
    $IdCarrera = 5;
    $IdEncuesta = 1;
    $IdFormulario = 1;
    
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Persona');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_formularios','gf');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');

    $encuesta = $this->ge->dame($IdEncuesta, $IdFormulario);
    $formulario = $this->gf->dame($IdFormulario);
    $carrera = $this->gc->dame($IdCarrera);
    $materia = $this->gm->dame($IdMateria);
    
    $docentes = $encuesta->listarDocentes($IdMateria, $IdCarrera);
    $secciones = $formulario->listarSeccionesCarrera($IdCarrera);
    
    $datos_secciones = array(); 
    foreach ($secciones as $i => $seccion) {
      $datos_subsecciones = array();
      //si la sección es referida a docentes
      if ($seccion->Tipo == 'D'){
        foreach ($docentes as $j => $docente) {
          $datos_subsecciones[$j] = array(
            'IdPersona' => $docente->IdPersona,
            'Apellido' => $docente->Apellido,
            'Nombre' => $docente->Nombre,
            'Preguntas' => $this->_dameDatosSeccion($seccion, $docente->IdPersona, $encuesta, $materia, $carrera)
          );
        }
      }
      //si la sección es referida a la materia (sección comun)
      else{
        $datos_subsecciones[0] =  array(
          'IdPersona' => 0,
          'Apellido' => '',
          'Nombre' => '',
          'Preguntas' => $this->_dameDatosSeccion($seccion, 0, $encuesta, $materia, $carrera)
        );
      }
      $datos_secciones[$i] = array(
        'Texto' => $seccion->Texto,
        'Subsecciones' => $datos_subsecciones
      );
    }
    $datos['encuesta'] = array(
      'año' => $encuesta->Año,
      'cuatrimestre' => $encuesta->Cuatrimestre,
      'fechaInicio' => $encuesta->FechaInicio,
      'fechaFin' => $encuesta->FechaFin);
    $datos['formulario'] = array(
      'titulo' => $formulario->Titulo,
      'descripcion' => $formulario->Descripcion);
    $datos['carrera'] = array(
      'nombre' => $carrera->Nombre);
    $datos['materia'] = array(
      'nombre' => $materia->Nombre);
    $datos['claves'] = $encuesta->cantidadClavesMateria($IdMateria, $IdCarrera);
    $datos['secciones'] = $datos_secciones;
    $this->load->view('informe_materia', $datos);
  }

  
  //funcion para responder solicitudes AJAX
  public function listarClavesAJAX(){
    $IdMateria = $this->input->post('IdMateria');
    $IdCarrera = $this->input->post('IdCarrera');
    $IdEncuesta = $this->input->post('IdEncuesta');
    $IdFormulario = $this->input->post('IdFormulario');
    //VERIFICAR
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->Encuesta->IdEncuesta = $IdEncuesta;
    $this->Encuesta->IdFormulario = $IdFormulario;
    $claves = $this->Encuesta->listarClavesMateria($IdMateria, $IdCarrera, 0,1000);
    foreach ($claves as $clave) {
      echo  "$clave->IdClave\t".
            "$clave->Clave\t".
            "$clave->Tipo\t".
            "$clave->Generada\t".
            "$clave->Utilizada\t\n";
    }
  }

  
}

?>