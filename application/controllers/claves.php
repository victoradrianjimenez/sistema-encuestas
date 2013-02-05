<?php

/**
 * 
 */
 
class Claves extends CI_Controller{
  
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
   * Muestra el listado de claves.
   * Última revisión: 2012-02-04 12:47 p.m.
   */
  public function listar($PagInicio=0){
    $this->ingresar();
  }
  
  /*
   * Ingresar la clave de acceso para llenar la encuesta
   * Última revisión: 2012-02-02 07:01 p.m.
   */
  public function ingresar(){
    //verifico si se envio clave
    $pclave = $this->input->post('clave');
    if($pclave){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      $this->data['clave'] = $pclave;

      //busco la clave ingresada
      $clave = $this->Encuesta->buscarClave($pclave);
      if ($clave){                
        //si la clave no fue utilizada
        if ($clave->utilizada == ''){
          //mostrar formulario para completar la encuesta
          $this->encuesta($clave);
        }
        else{
          $this->data['mensaje'] = "Clave de Acceso Utilizada el $clave->utilizada.";
          $this->load->view('ingreso_clave', $this->data);
        }
      }
      else{
        $this->data['mensaje'] = 'Clave de Acceso Inválida';
        $this->load->view('ingreso_clave', $this->data);
      }
    }
    else{
      $this->data['clave'] = '';
      $this->data['mensaje'] = '';
      $this->load->view('ingreso_clave', $this->data);
    }
  }
  
  /* 
   * Función auxiliar para generar el formulario de encuesta
   * Última revisión: 2012-02-02 07:38 p.m.
   */
  private function _datosItems($seccion){
    $items = $seccion->listarItems();
    //por cada item
    $datos_items = array();
    foreach ($items as $k => $item) {
      $datos_items[$k]['item'] = $item;
      $datos_items[$k]['opciones'] = $item->listarOpciones();
    }
    return $datos_items;
  }

  /* 
   * Mostrar el formulario al alumno para que llene la encuesta.
   * Nota: El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   * Última revisión: 2012-02-02 07:38 p.m.
   */
  public function encuesta($clave){
    $this->load->model('Usuario');
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Clave');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_usuarios', 'gu');
    $this->load->model('Gestor_materias', 'gm');
    $this->load->model('Gestor_carreras', 'gc');
    $this->load->model('Gestor_formularios', 'gf');
    $this->load->model('Gestor_encuestas', 'ge');
    
    $formulario = $this->gf->dame($clave->idFormulario);
    $encuesta = $this->ge->dame($clave->idEncuesta, $clave->idFormulario);
    $carrera = $this->gc->dame($clave->idCarrera);
    $materia = $this->gm->dame($clave->idMateria);  
    $docentes = $materia->listarDocentes();
    $secciones = $formulario->listarSeccionesCarrera($clave->idCarrera);
    //por cada seccion
    $datos_secciones = array();
    foreach ($secciones as $i => $seccion) {
      $datos_subsecciones = array();
      //si la pregunta es referida a docentes
      if($seccion->tipo == 'D'){
        //por cada docente
        foreach ($docentes as $j => $docente) {
          //guardo los datos de la subsección
          $datos_subsecciones[$j]['docente'] = $docente;
          $datos_subsecciones[$j]['items'] = $this->_datosItems($seccion);
        }
      }
      else{
        //si la sección es común, habrá una única subsección
        $datos_subsecciones[0]['docente'] = $this->Usuario;
        $datos_subsecciones[0]['items'] = $this->_datosItems($seccion);
      }
      //guardo los datos de la sección
      $datos_secciones[$i]['seccion'] = $seccion;
      $datos_secciones[$i]['subsecciones'] = $datos_subsecciones;
    }
    $this->data['clave'] = &$clave;
    $this->data['formulario'] = &$formulario;
    $this->data['carrera'] = &$carrera;
    $this->data['materia'] = &$materia;
    $this->data['secciones'] = &$datos_secciones;
    $this->load->view('formulario_encuesta', $this->data);
  }

  /* 
   * Mostrar el formulario al alumno para que llene la encuesta.
   * Nota: El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   * Última revisión: 2012-02-02 07:38 p.m.
   */
  public function responder(){
    $error = false;
    $res = null;
    //verifico si se enviaron los datos por POST
    $pclave = $this->input->post('clave');
    if (!$pclave){
      //la clave es invalida
      $res = 'Hubo un error en el formulario.';
      $error = true;
    }
    if (!$error){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      $clave = $this->Encuesta->buscarClave($pclave);
      //verifico si la clave usada es válida
      if (!$clave){
        //la clave es invalida
        $res = 'La clave de acceso utilizada es invalida.';
        $error = true;
      }
    }
    if (!$error){
      //verifico si la clave no fue usada antes
      if ($clave->utilizada != ''){
        //la clave ya fue utilizada
        $res = 'La clave de acceso ya fue utilizada.';
        $error = true;
      }
    }
    if (!$error){ 
      //leo todos los datos enviados
      $inputs = $this->input->post(NULL, TRUE);
      //verifico integridad de los datos
      foreach ($inputs as $var => $respuesta) {
        //si el dato es una respuesta
        if (strpos($var,'idPregunta') !== false){
          $idDocente = null;
          //verifico que haya mandado ID de la pregunta, el tipo (el ID del docente es opcional)
          if (sscanf($var, "idPregunta_%u_%c_%u", $idPregunta, $tipo, $idDocente)>1){
            if($tipo == 'T' || $tipo == "X")
              $res = $clave->altaRespuesta($idPregunta, ($idDocente!=0)?$idDocente:NULL, NULL, ($respuesta!='')?$respuesta:NULL);
            else
              $res = $clave->altaRespuesta($idPregunta, ($idDocente!=0)?$idDocente:NULL, ($respuesta!='')?$respuesta:NULL, NULL);
            //si hubo error al dar de alta una respuesta terminar el bucle
            if(!is_numeric($res)){
              $error = true;
              break;
            }
          }
        }
      }
      //establezco que la clave ya fue utilizada
      if (!$error){
        $res = $clave->registrar();
        $error = ($res!='ok')?TRUE:FALSE;
      }
    }
    if ($error){
      //ELIMINAR RESPUESTAS YA DADAS DE ALTA
      $this->data['mensaje'] = $res;
      $this->data['link'] = site_url(""); //hacia donde redirigirse
    }
    else{
      $this->data['mensaje'] = 'Sus respuestas fueron guardados correctamente. Muchas gracias por participar de nuestra encuesta.';
      $this->data['link'] = site_url(""); //hacia donde redirigirse
    }
    $this->load->view('resultado_operacion', $this->data);
  }
  
  /*
   * Generar claves de acceso
   * POST: idEncuesta, idFormulario, idCarrera, idMateria, tipo, cantidad
   * Última revisión: 2012-02-04 04:21 p.m.
   */
   public function generar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //chequeo parámetros de entrada
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('tipo','Tipo','alpha|exact_length[1]|required');
    $this->form_validation->set_rules('cantidad','Cantidad','is_natural_no_zero');      
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      
      $tipo = $this->input->post('tipo',TRUE);
      
      //VER POR TIPO DE CLAVE!!!!!!!!!!!!
      
      $cantidad = $this->input->post('cantidad',TRUE);
      $cnt = 0;
      for ($i=0; $i<$cantidad; $i++){
        $clave = $this->Encuesta->altaClave( $this->input->post('idEncuesta',TRUE), $this->input->post('idFormulario',TRUE), $this->input->post('idCarrera',TRUE), $this->input->post('idMateria',TRUE), $tipo);
        if (is_numeric($clave)){
          $cnt++;
        }
      }
      $this->data['mensaje'] = ($cnt>0)?"La operación se realizó con éxito. Se generaron $cnt claves.":'No se generaron claves.';
      $this->data['link'] = site_url("claves"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->index();
    }
  }
   
   
}

?>