<?php

/**
 * 
 */
 
class Claves extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    $this->data['resultadoTipo'] = ALERT_ERROR;
    $this->data['resultadoOperacion'] = null;
  }
    
  public function index(){
    $this->ingresar();
  }
  
  /*
   * Gestion de claves de acceso. Aqui el usuario elige la carrera, materia y encuesta para ver listado de claves a imprimir
   */
  public function claves_acceso(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->load->model('Departamento');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_carreras','gc');
      $this->load->model('Gestor_departamentos','gd');
      $idEncuesta = (int)$this->input->post('idEncuesta');
      $idFormulario = (int)$this->input->post('idFormulario');
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      //cargo librerias y modelos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $claves = $encuesta->listarClavesMateria($idMateria, $idCarrera);
      $lista = array();
      foreach ($claves as $i => $clave) {
        $materia = $this->gm->dame($clave->idMateria);
        $carrera = $this->gc->dame($clave->idCarrera);
        $departamento =  $this->gd->dame($carrera->idDepartamento);
        $lista[$i] = array(
          'materia' => $materia,
          'carrera' => $carrera,
          'departamento' => $departamento,
          'clave' => $clave
        );
      }
      $this->data['lista'] = &$lista;
      $this->load->view('vista_claves', $this->data); 
    }
    else{
      $this->load->view('claves_acceso', $this->data);
    }
  }
  
  
  /************************************************
   * Muestra el listado de claves para una encuesta, materia y carrera en particular
   * Última revisión: 2012-02-04 12:47 p.m.
   */
  public function listar($idMateria, $idCarrera, $idEncuesta, $idFormulario, $pagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    $this->load->model('Departamento');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_departamentos','gd');
    $this->load->model('Gestor_encuestas','ge');
    
    //obtengo lista de claves
    $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
    $materia = $this->gm->dame($idMateria);
    $lista = $encuesta->listarClavesMateria($idMateria, $idCarrera, $pagInicio, PER_PAGE);
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("claves/listar/$idMateria/$idCarrera/$idEncuesta/$idFormulario"),
      'total_rows' => 100,//$encuesta->cantidadClavesMateria($idMateria, $idCarrera),
      'uri_segment' => 7
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Departamentos
    $this->data['materia'] = &$materia; //datos de la materia a la que pertenecen las claves
    $this->data['encuesta'] = &$encuesta; 
    echo '1';
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    echo '2';
    $this->load->view('lista_claves_materia', $this->data);
    
  }
  
  
  /*
   * Ingresar la clave de acceso para llenar la encuesta
   */
  public function ingresar(){
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    //verifico los datos del POST
    $this->form_validation->set_rules('clave', 'Clave de Acceso', 'callback_validar_clave_acceso');
    if ($this->form_validation->run()){
      $clave = $this->Encuesta->buscarClave($this->input->post('clave'));
      //mostrar formulario para completar la encuesta
      $this->encuesta($clave);
      return;
    }
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->load->view('ingreso_clave', $this->data);
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
      if($seccion->tipo == SECCION_TIPO_DOCENTE){
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
        $res = $clave->marcarUtilizada();
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
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('cantidad','Cantidad','is_natural_no_zero');      
    if($this->form_validation->run()){
      $this->load->model('Encuesta');
      $cantidad = (int)$this->input->post('cantidad');
      $cnt = 0;
      for ($i=0; $i<$cantidad; $i++){
        $clave = $this->Encuesta->altaClave((int)$this->input->post('idEncuesta'), 
                                            (int)$this->input->post('idFormulario'), 
                                            (int)$this->input->post('idCarrera'), 
                                            (int)$this->input->post('idMateria'));
        if (is_numeric($clave)){$cnt++;}
      }
      if ($cnt == $cantidad){
        $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con éxito. Se generaron $cnt claves.");
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      elseif($cnt==0) {
        $this->session->set_flashdata('resultadoOperacion', "Se produjo un error. No se generaron claves.");
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR); 
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con problemas. Se generaron $cnt claves.");
        $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      }
      redirect('claves');
    }
    $this->load->view('claves_acceso', $this->data);
  }
   
   
  /*
   * Verificar la calve de acceso dada por el alumno
   */
  function validar_clave_acceso($pclave=null){
    if ($pclave){
      $clave = $this->Encuesta->buscarClave($pclave);
      if ($clave){                
        //si la clave no fue utilizada
        if ($clave->utilizada == ''){
          return TRUE;
        }
        else{
          $this->form_validation->set_message('validar_clave_acceso', "Clave de Acceso Utilizada el $clave->utilizada.");
        }
      }
      else{
        $this->form_validation->set_message('validar_clave_acceso', 'Clave de Acceso Inválida');
      }
    }
    else{
      $this->form_validation->set_message('validar_clave_acceso', 'La clave de acceso no puede ser nula.');
    }
    return FALSE;
  }
  
  //funcion para responder solicitudes AJAX
  public function listarClavesMateriaAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $idEncuesta = $this->input->post('idEncuesta');
      $idFormulario = $this->input->post('idFormulario');
      $idMateria = $this->input->post('idMateria');
      $idCarrera = $this->input->post('idCarrera');
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      $this->load->model('Gestor_encuestas','ge');
      //obtengo lista de claves
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      if ($encuesta){
        $claves = $encuesta->listarClavesMateria($idMateria, $idCarrera, 0, 1000);
        echo "\n";
        foreach ($claves as $clave) {
          echo  "$clave->idClave\t".
                "$clave->clave\t".
                "$clave->tipo\t".
                "$clave->generada\t".
                "$clave->utilizada\t\n";
        }
      }
    }
  }
}

?>