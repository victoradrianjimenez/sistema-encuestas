<?php

/**
 * 
 * 
 * 
 * 
 * _datosItems($seccion)
 * responder()
 * encuesta()
 * ingresar()
 * generar()
 * 
 */
 
class Claves extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
    
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de claves.
   */
  public function listar($PagInicio=0){
    
  }
  
  private function _datosItems($seccion){
    $items = $seccion->listarItems();
    //por cada item
    $datos_items = array();
    foreach ($items as $k => $item) {
      $opciones = $item->listarOpciones();
      //por cada opcion
      $datos_opciones = array();
      foreach ($opciones as $l => $opcion) {
        $datos_opciones[$l]=array(
          'IdOpcion' => $opcion->IdOpcion,
          'Texto' => $opcion->Texto
        );
      }
      $datos_items[$k] = array(
        'IdPregunta' => $item->IdPregunta,
        'Texto' => $item->Texto,
        'Descripcion' => $item->Descripcion,
        'Tipo' => $item->Tipo,
        'Obligatoria' => $item->Obligatoria,
        'LimiteInferior' => $item->LimiteInferior,
        'LimiteSuperior' => $item->LimiteSuperior,
        'Paso' => $item->Paso,
        'Unidad' => $item->Unidad,
        'Tamaño' => $item->Tamaño,
        'Opciones' => $datos_opciones
      );
    }
    return $datos_items;
  }

  public function responder(){
    //verifico si se enviaron los datos por POST
    $Clave = $this->input->post('Clave');
    if ($Clave){
      $clave = $this->Encuesta->buscarClave($Clave);
      //verifico si la clave usada es válida
      if ($clave){
        //verifico si la clave no fue usada antes
        if ($clave->Utilizada = ''){
          //leo todos los datos enviados
          $inputs = $this->input->post(NULL, TRUE);
          //verifico integridad de los datos
          $error = false;
          foreach ($inputs as $var => $Respuesta) {
            //si el dato es una respuesta
            if (strpos($var,'IdPregunta') !== false){
              //verifico que haya mandado ID de la pregunta y del docente
              if (sscanf($var, "IdPregunta_%u_%u", $IdPregunta, $IdDocente)!=2){
                $error = true;
                break;
              }
            }
          }
          if(!$error){
            foreach ($inputs as $var => $Respuesta) {
              //si el dato es una respuesta
              if (strpos($var,'IdPregunta') !== false){
                //doy de alta la pregunta
                $this->Clave->altaRespuesta($IdPregunta, $IdDocente, $Respuesta);
              }
            }
          }
          else {
            //los datos son incorrectos
          }
        }
        else {
          //la clave ya fue utilizada
        }
      }
      else{
        //la clave es invalida    
      }
    }
  }

  /* 
   * Mostrar el formulario al alumno para que llene la encuesta.
   * El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   */
  public function encuesta(){
    $IdMateria = 5;
    $IdCarrera = 5;
    $IdFormulario = 1;
    $IdEncuesta = 1;
    $Clave = '9086E078460';
  
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Clave');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_materias', 'gm');
    $this->load->model('Gestor_carreras', 'gc');
    $this->load->model('Gestor_formularios', 'gf');
    $this->load->model('Gestor_encuestas', 'ge');
    
    $formulario = $this->gf->dame($IdFormulario);
    $encuesta = $this->ge->dame($IdEncuesta, $IdFormulario);
    $carrera = $this->gc->dame($IdCarrera);
    $materia = $this->gm->dame($IdMateria);  
    $docentes = $materia->listarDocentes();
    $secciones = $formulario->listarSeccionesCarrera($IdCarrera);
    $clave = $this->Encuesta->buscarClave($Clave);
    
    //por cada seccion
    $datos_secciones = array();
    foreach ($secciones as $i => $seccion) {
      $datos_subsecciones = array();
      //si la pregunta es referida a docentes
      if($seccion->Tipo == 'D'){
        //por cada docente
        foreach ($docentes as $j => $docente) {
          //guardo los datos de la subsección
          $datos_subsecciones[$j] = array(
            'IdDocente' => $docente->IdPersona,
            'Nombre' => $docente->Nombre,
            'Apellido' => $docente->Apellido,
            'Items' => $this->_datosItems($seccion)
          );
        }
      }
      else{
        //si la sección es común, habrá una única subsección
        $datos_subsecciones[0] = array(
          'IdDocente' => 0,
          'Nombre' => '',
          'Apellido' => '',
          'Items' => $this->_datosItems($seccion)
        );
      }
      //guardo los datos de la sección
      $datos_secciones[$i] = array(
        'Texto' => $seccion->Texto,
        'Descripcion' => $seccion->Descripcion,
        'Tipo' => $seccion->Tipo,
        'Subsecciones' => $datos_subsecciones
      );
    }
    $data = array(
      'clave' => array(
          'Clave' => $clave->Clave),
      'formulario' => array(
          'IdFormulario' => $formulario->IdFormulario,
          'Titulo' => $formulario->Titulo,
          'Descripcion' => $formulario->Descripcion), 
      'carrera' => array(
          'Nombre' => $carrera->Nombre),
      'materia' => array(
          'Nombre' => $materia->Nombre,
          'Codigo' => $materia->Codigo),
      'secciones' => $datos_secciones
    );
    $this->load->view('formulario_encuesta', $data);
  }

  
  /*
   * Ingresar la clave de acceso para llenar la encuesta
   */
  public function ingresar(){
    //verifico si se envio clave
    $pClave = $this->input->post('clave');
    if($pClave){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      //busco la clave ingresada
      $clave = $this->Encuesta->buscarClave($pClave);
      if ($clave){
        //si la clave no fue utilizada
        if ($clave->Utilizada == ''){
          $data = '';
          $this->load->view('formulario_encuesta', $data);
        }
        else{
          $data['clave'] = $pClave;
          $data['mensaje'] = "Clave de Acceso Utilizada el $clave->Utilizada.";
          $this->load->view('ingreso_clave', $data);
        }
      }
      else{
        $data['clave'] = $pClave;
        $data['mensaje'] = 'Clave de Acceso Inválida';
        $this->load->view('ingreso_clave', $data);
      }
    }
    else{
      $data['clave'] = '';
      $data['mensaje'] = '';
      $this->load->view('ingreso_clave', $data);
    }
  }
  
  /*
   * Generar claves de acceso
   * POST: IdEncuesta, IdFormulario, IdCarrera, IdMateria, Tipo, Cantidad
   */
   public function generar(){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $this->form_validation->set_rules('IdEncuesta','ID Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdFormulario','ID Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('Tipo','Tipo','alpha|exact_length[1]|required');
    $this->form_validation->set_rules('Cantidad','Cantidad','is_natural_no_zero');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      
      //VER POR TIPO DE CLAVE!!!!!!!!!!!!
      
      $cantidad = $this->input->post('Cantidad',TRUE);
      $cnt = 0;
      for ($i=0; $i<$cantidad; $i++){
        $clave = $this->Encuesta->altaClave( $this->input->post('IdEncuesta',TRUE),
                              $this->input->post('IdFormulario',TRUE),
                              $this->input->post('IdCarrera',TRUE),
                              $this->input->post('IdMateria',TRUE),
                              $this->input->post('Tipo',TRUE));
        //VERIFICAR SI SE CREARON!!!!!!!!!
        $cnt++;
      }
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = ($cnt>0)?"La operación se realizó con éxito. Se generaron $cnt claves.":'No se generaron claves.';
      $data['link'] = site_url("claves"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->index();
    }
  }
   
   
}

?>