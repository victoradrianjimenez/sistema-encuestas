<?php

/**
 * Controlador para la gestion de claves de acceso
 */
 
class Claves extends CI_Controller{
  
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
    
  public function index(){
    $this->ingresar();
  }
  
  /*
   * Gestion de claves de acceso. Aqui el usuario elige la carrera, materia y encuesta para ver listado de claves a imprimir
   */
  public function claves_acceso(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
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
    
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    $this->form_validation->set_rules('idCarrera','Carrera','required|is_natural_no_zero');
    $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
    $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
    if($this->form_validation->run()){
      //cargo librerias y modelos
      $encuesta = $this->ge->dame($idEncuesta, $idFormulario);
      $materia = $this->gm->dame($idMateria);
      $carrera = $this->gc->dame($idCarrera);
      $departamento =  $this->gd->dame($carrera->idDepartamento);
      $claves = $encuesta->listarClavesMateria($idMateria, $idCarrera);
      $lista = array();
      foreach ($claves as $i => $clave) {
        $lista[$i] = array(
          'materia' => $materia,
          'carrera' => $carrera,
          'departamento' => $departamento,
          'clave' => $clave
        );
      }
      $this->data['lista'] = &$lista;
      $this->load->view('vista_claves', $this->data);
      return; 
    }
    if ($idEncuesta && $idFormulario) $this->Encuesta = $this->ge->dame($idEncuesta, $idFormulario);
    if ($idMateria) $this->Materia = $this->gm->dame($idMateria);
    if ($idCarrera) $this->Carrera = $this->gc->dame($idCarrera);
    $this->data['encuesta'] = &$this->Encuesta;
    $this->data['materia'] = &$this->Materia;
    $this->data['carrera'] = &$this->Carrera;
    $this->load->view('claves_acceso', $this->data);
  }

  /* 
   * Mostrar el formulario al alumno para que llene la encuesta.
   * Nota: El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   */
  public function encuesta($clave=null){
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
          $items = $seccion->listarItems();
          $datos_items = array();
          foreach ($items as $k => $item) {
            $datos_items[$k]['item'] = $item;
            $datos_items[$k]['opciones'] = $item->listarOpciones();
          }
          //guardo los datos de la subsección
          $datos_subsecciones[$j]['docente'] = $docente;
          $datos_subsecciones[$j]['items'] = $datos_items;
        }
      }
      else{
        $items = $seccion->listarItems();
        $datos_items = array();
        foreach ($items as $k => $item) {
          $datos_items[$k]['item'] = $item;
          $datos_items[$k]['opciones'] = $item->listarOpciones();
        }
        //si la sección es común, habrá una única subsección
        $datos_subsecciones[0]['docente'] = $this->Usuario;
        $datos_subsecciones[0]['items'] = $datos_items;
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
   * Ingresar la clave de acceso para llenar la encuesta
   * Nota: El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   */
  public function ingresar(){
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    //verifico los datos del POST
    $this->form_validation->set_rules('clave', 'Clave de Acceso', 'callback_validar_clave_acceso');
    if ($this->form_validation->run()){
      $clave = $this->Encuesta->buscarClave($this->input->post('clave'));
      if ($clave){
        $this->encuesta($clave);
        return;
      }
      $this->session->set_flashdata('resultadoOperacion', 'Sus respuestas fueron guardados correctamente. Muchas gracias por participar de nuestra encuesta.');
      $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS); 
      redirect('/');
    }
    $this->load->view('ingreso_clave', $this->data);
  }

  /*
   * Generar formulario para contestar preguntas
   * Nota: El formulario de encuestas se compone de: secciones/subsecciones/items/opciones
   */
  public function responder(){
    $this->load->model('Clave');
    $this->load->model('Encuesta');
    //verifico los datos del POST
    $this->form_validation->set_rules('clave', 'Clave de Acceso', 'callback_validar_clave_acceso');
    if ($this->form_validation->run()){
      $clave = $this->Encuesta->buscarClave($this->input->post('clave'));
      if ($clave){
        $error = false;
        $res = null;
        if ($clave->utilizada != ''){
          //la clave ya fue utilizada
          $res = 'La clave de acceso ya fue utilizada.';
          $error = true;
        }
        if (!$error){ 
          //leo todos los datos enviados
          $inputs = $this->input->post(NULL, TRUE);
          //verifico integridad de los datos
          foreach ($inputs as $var => $respuesta) {
            //si el dato es una respuesta
            if (strpos($var,'idPregunta') !== false){
              $idDocente = NULL;
              //verifico que haya mandado ID de la pregunta, el tipo y el ID del docente (es opcional)
              if (sscanf($var, "idPregunta_%u_%c_%u", $idPregunta, $tipo, $idDocente)>1){
                if($tipo == TIPO_TEXTO_SIMPLE || $tipo == TIPO_TEXTO_MULTILINEA)
                  $res = $clave->altaRespuesta($idPregunta, ($idDocente)?$idDocente:NULL, NULL, ($respuesta!='')?$respuesta:NULL);
                else
                  $res = $clave->altaRespuesta($idPregunta, ($idDocente)?$idDocente:NULL, ($respuesta!='')?$respuesta:NULL, NULL);
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
            $error = ($clave->marcarUtilizada() != PROCEDURE_SUCCESS)?TRUE:FALSE;
          }
          else{
            $this->session->set_flashdata('resultadoOperacion', $res);
            $this->session->set_flashdata('resultadoTipo', ALERT_ERROR); 
          }
        }
        $this->session->set_flashdata('resultadoOperacion', 'Sus respuestas fueron guardados correctamente. Muchas gracias por participar de nuestra encuesta.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS); 
      }
      else{
        $this->data['resultadoOperacion'] = 'Se produjo un error al leer los datos de la clave de acceso. Por favor intente nuevamente.';
        $this->data['resultadoTipo'] = ALERT_ERROR;
      }
    }
    redirect('claves/ingresar');
  }

  /*
   * Generar claves de acceso
   * POST: idEncuesta, idFormulario, idCarrera, idMateria, cantidad
   */
  public function generar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    $this->load->model('Encuesta');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    //chequeo parámetros de entrada
    $this->form_validation->set_rules('idEncuesta','Encuesta','is_natural_no_zero|required');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('cantidad','Cantidad','is_natural_no_zero');      
    if($this->form_validation->run()){
      $idMateria = (int)$this->input->post('idMateria');
      $idCarrera = (int)$this->input->post('idCarrera');
      $cantidad = (int)$this->input->post('cantidad');
      $guardarCantidad = (bool)$this->input->post('guardarCantidad');
      $materia = $this->gm->dame($idMateria);
      $carrera = $this->gc->dame($idCarrera);
      if (!$materia || !$carrera){
        $this->session->set_flashdata('resultadoOperacion', "Los datos ingresados son inválidos.");
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR); 
        redirect('claves/claves_acceso');
      }
      $cnt = 0;
      $this->Encuesta->idEncuesta = (int)$this->input->post('idEncuesta');
      $this->Encuesta->idFormulario = (int)$this->input->post('idFormulario');
      for ($i=0; $i<$cantidad; $i++){
        $clave = $this->Encuesta->altaClave($idMateria, $idCarrera);
        if (is_numeric($clave)){$cnt++;}
      }
      if ($cnt == $cantidad){
        if ($guardarCantidad) $materia->asignarCantidadClaves($idCarrera, $cantidad);
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
      redirect('claves/claves_acceso');
    }
    $this->data['encuesta'] = &$this->Encuesta;
    $this->data['materia'] = &$this->Materia;
    $this->data['carrera'] = &$this->Carrera;
    $this->load->view('claves_acceso', $this->data);
  }
   
  /*
   * Verificar la clave de acceso dada por el alumno
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
    //if (!$this->ion_auth->logged_in()){return;}
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
        $claves = $encuesta->listarClavesMateria($idMateria, $idCarrera);
        echo "\n";
        foreach ($claves as $clave) {
          echo  "$clave->idClave\t".
                "$clave->clave\t".
                "$clave->generada\t".
                date('d/m/Y G:i:s', strtotime($clave->utilizada))."\t\n";
        }
      }
    }
  }
}

?>