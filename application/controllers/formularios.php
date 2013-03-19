<?php

/**
 * Controlador para la gestión de formularios
 */
class Formularios extends CI_Controller{

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
    $this->listar();
  }
  
  /*
   * Muestra el listado de formularios
   */
  public function listar($PagInicio=0){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    
    //obtengo lista de formularios
    $lista = $this->gf->listar($PagInicio, PER_PAGE);

    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("formularios/listar"),
      'total_rows' => $this->gf->cantidad()
    ));
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los formularios
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->load->view('lista_formularios', $this->data);
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
   * Mostrar el formulario, tal como lo vería el alumno
   * POST: idFormulario, idMateria
   */
  public function ver(){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
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
    $this->load->model('Gestor_carreras', 'gc');
    $this->load->model('Gestor_formularios', 'gf');
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero');
    if($this->form_validation->run()){
      $idFormulario = (int)$this->input->post('idFormulario');
      $idCarrera = (int)$this->input->post('idCarrera');
      $formulario = $this->gf->dame($idFormulario);
      if($idCarrera){
        $carrera = $this->gc->dame($idCarrera);
        $secciones = $formulario->listarSeccionesCarrera($idCarrera);
      }
      else{
        $carrera = $this->Carrera;
        $secciones = $formulario->listarSecciones();
        $idCarrera = false;
      }
      $docentes = array($this->Usuario);  
      //por cada seccion
      $datos_secciones = array();
      foreach ($secciones as $i => $seccion) {
        $datos_subsecciones = array();
        $items = ($idCarrera) ? $seccion->listarItemsCarrera($idCarrera) : $seccion->listarItems();
        //por cada item
        $datos_items = array();
        foreach ($items as $k => $item) {
          $datos_items[$k]['item'] = $item;
          $datos_items[$k]['opciones'] = $item->listarOpciones();
        }
        //si la sección es común, habrá una única subsección
        $datos_subsecciones[0]['docente'] = $this->Usuario;
        $datos_subsecciones[0]['items'] = $datos_items;
        //guardo los datos de la sección
        $datos_secciones[$i]['seccion'] = $seccion;
        $datos_secciones[$i]['subsecciones'] = $datos_subsecciones;
      }
      $this->Materia->nombre = '[Nombre Asignatura]';
      $this->data['clave'] = &$this->Clave;
      $this->data['formulario'] = &$formulario;
      $this->data['carrera'] = ($carrera)?$carrera:$this->Carrera;
      $this->data['materia'] = &$this->Materia;
      $this->data['secciones'] = &$datos_secciones;
      $this->load->view('formulario_encuesta', $this->data);
    }
    else {
     show_404();
    }
  }

  
  /*
   * Muestra el formulario de edicion de formularios
   */
  public function editar(){
    
    
    
    
    
    
    
    
    
    
    $this->data['tituloFormulario'] = 'Editar Formulario';
    $this->data['urlFormulario'] = site_url('formularios/editar');
    $this->load->view('editar_formulario', $this->data);
  }
  
  
  /*
   * Recepción del formulario para agregar nuevo formulario con sus secciones y preguntas
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('formularios/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('titulo','Título','alpha_dash_space|max_length[200]|required');
    $this->form_validation->set_rules('descripcion','Descripción','alpha_dash_space|max_length[200]');
    $this->form_validation->set_rules('preguntasAdicionales','Preguntas adicionales','is_natural_no_zero|required');      
    if($this->form_validation->run()){
      //cargo modelos y librerias necesarias
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Gestor_formularios','gf');
      //leo los datos enviados por post, y los almaceno en un array
      $entradas = $this->input->post(NULL, TRUE);
      $datosSecciones = array();
      $res = null;
      $error = false;
      foreach ($entradas as $key => $x) {
        if (strpos($key, 'textoSeccion') !== false) {
          if (sscanf($key, "textoSeccion_%d",$i) == 1)
            $datosSecciones[$i]['texto'] = $x;
          else{
            $res="El texto de la sección no puede ser nulo.";
            $error=true;
            break;
          }
        }
        elseif (strpos($key, 'descripcionSeccion') !== false) {
          sscanf($key, "descripcionSeccion_%d", $i);
          $datosSecciones[$i]['descripcion'] = $x;
        }
        elseif (strpos($key, 'tipoSeccion') !== false) {
          if(sscanf($key, "tipoSeccion_%d", $i) == 1)
            $datosSecciones[$i]['tipo'] = $x;
          else{
            $res="El tipo de sección es incorrecto.";
            $error=true;
            break;
          }
        }
        elseif (strpos($key, 'idPregunta') !== false) {
          if(sscanf($key, "idPregunta_%d_%d", $i, $j) == 2)
            $datosSecciones[$i]['preguntas'][$j] = $x;
          else{
            $res="El identificador de la pregunta es incorrecto.";
            $error=true;
            break;
          }
        }
      }
      if(!$error){
        //doy de alta el formulario primero
        $res = $this->gf->alta($this->input->post('nombre', TRUE), $this->input->post('titulo', TRUE), $this->input->post('descripcion', TRUE), $this->input->post('preguntasAdicionales', TRUE));
        $error = !is_numeric($res);
      }
      if(!$error){
        $this->Formulario->idFormulario = (int)$res;
        foreach ($datosSecciones as $i => $seccion) {
          $res = $this->Formulario->altaSeccion(NULL, $seccion['texto'], $seccion['descripcion'], $seccion['tipo']);
          if (!is_numeric($res)){
            $error = true;
            break;
          }
          if(!isset($seccion['preguntas'])){
            $res = "Las secciones no pueden estar vacías.";
            $error = true;
            break;
          }
          $this->Seccion->idSeccion = (int)$res;
          $this->Seccion->idFormulario = $this->Formulario->idFormulario;
          foreach ($seccion['preguntas'] as $j => $pregunta) {
            $res = $this->Seccion->altaItem($pregunta, NULL, $j);
            echo $pregunta.' '.NULL.' '.$j;
            if ($res != PROCEDURE_SUCCESS){
              $error = true;
              break;
            }
          }
          if ($error) break;
        }
      }
      if($error){
        
        $this->gf->baja($this->Formulario->idFormulario);
      }
      //cargo vista para mostrar resultado
      $this->data['mensaje'] = (!$error)?"La operación se realizó con éxito. El ID del nuevo formulario es ".$this->Formulario->idFormulario.".":$res;
      $this->data['link'] = site_url("formularios/listar"); //hacia donde redirigirse
      /////////////////////////////////////$this->load->view('resultado_operacion', $this->data);
    }
    else{
      $this->data['tituloFormulario'] = 'Nuevo Formulario';
      $this->data['urlFormulario'] = site_url('formularios/nuevo');
      $this->load->view('editar_formulario', $this->data);
    }
  }
  
  
  /*
   * Recepción del formulario para eliminar un formulario
   * POST: idFormulario
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('formularios/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_formularios','gf');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gf->baja($this->input->post('idFormulario',TRUE));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'El formulario se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('formularios/listar');
  }


  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $this->load->model('Formulario');
      $this->load->model('Gestor_formularios','gf');
      $formularios = $this->gf->buscar($this->input->post('buscar'));
      echo "\n";
      foreach ($formularios as $formulario) {
        echo  "$formulario->idFormulario\t".
              "$formulario->nombre\t".
              date('d/m/Y', strtotime($formulario->creacion))."\t\n";
      }
    }
  }
  
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function listarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->listar(0,1000);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->idFormulario\t".
            "$formulario->nombre\t".
            date('d/m/Y', strtotime($formulario->creacion))."\t\n";
    }
  }
}

?>