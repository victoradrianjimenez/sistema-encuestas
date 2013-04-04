<?php

/**
 * Controlador para la gestión de Preguntas
 */
class Preguntas extends CI_Controller {
  
  var $data = array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    //leo los datos del usuario logueado
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    //leo los mensajes generados en la página anterior
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de preguntas.
   */
  public function listar($PagInicio=0){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('/');
    }
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Pregunta');
    $this->load->model('Carrera');
    $this->load->model('Gestor_preguntas','gp');
    $this->load->model('Gestor_carreras','gc');
    
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;

    //obtengo lista de preguntas
    $preguntas = $this->gp->listar($PagInicio, PER_PAGE);
    $lista = array(); //datos para mandar a la vista
    foreach ($preguntas as $i => $pregunta) {
      $lista[$i] = array(
        'pregunta' => $pregunta,
        'tipo' => $pregunta->tipo()
      );
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("preguntas/listar"),
      'total_rows' => $this->gp->cantidad()
    ));
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las preguntas
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_preguntas', $this->data);
  }

  /*
   * Recepción del formulario para agregar nueva pregunta
   * POST: texto, descripcion, tipo, ordenInverso, indiceNulo, unidad, limiteInferior, limiteSuperior, paso, textoOpcion[]
   */
  public function nueva(){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('preguntas/listar');
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Pregunta');
    $this->load->model('Opcion');
    $this->load->model('Gestor_preguntas','gp');
    
    //leer datos del post
    $this->Pregunta->tipo = $this->input->post('tipo');
    $this->Pregunta->texto = $this->input->post('texto', TRUE);
    $this->Pregunta->descripcion = ($this->input->post('descripcion'))?$this->input->post('descripcion', TRUE):NULL;
    $this->Pregunta->unidad = ($this->input->post('unidad'))?$this->input->post('unidad', TRUE):NULL;
    $this->Pregunta->limiteInferior = ($this->input->post('limiteInferior'))?$this->input->post('limiteInferior'):NULL;
    $this->Pregunta->limiteSuperior = ($this->input->post('limiteSuperior'))?$this->input->post('limiteSuperior'):NULL;
    $this->Pregunta->paso = ($this->input->post('paso'))?$this->input->post('paso'):NULL;
    $this->Pregunta->modoIndice = ((bool)$this->input->post('ordenInverso'))?MODO_INDICE_INVERSO:MODO_INDICE_NORMAL;
    if ((bool)$this->input->post('indiceNulo')) $this->Pregunta->modoIndice = MODO_INDICE_NULO;
    
    $datosOpciones = $this->input->post('textoOpcion');

    //verifico datos POST
    $error = false;
    $this->form_validation->set_rules('texto','Texto','max_length[250]|required');
    $this->form_validation->set_rules('descripcion','Descripción','max_length[250]');
    $this->form_validation->set_rules('unidad','Unidad','alpha_dash_space|max_length[10]');
    $this->form_validation->set_rules('tipo','Tipo de pregunta','alpha|exact_length[1]');
    switch ($this->Pregunta->tipo){
    case TIPO_NUMERICA:
      $this->form_validation->set_rules('limiteInferior','Limite Inferior','numeric|required');
      $this->form_validation->set_rules('limiteSuperior','Limite Superior','numeric|required');
      $this->form_validation->set_rules('paso','Paso','numeric|required');
      break;
    case TIPO_SELECCION_SIMPLE:
      if (!$datosOpciones || count($datosOpciones)==0) $error = true;
      break;
    }
    if($this->form_validation->run() && !$error){
      $res = $this->gp->alta( $this->Pregunta->texto, $this->Pregunta->descripcion,  
                              $this->Pregunta->tipo, $this->Pregunta->modoIndice, $this->Pregunta->limiteInferior,  
                              $this->Pregunta->limiteSuperior, $this->Pregunta->paso, $this->Pregunta->unidad);
      //si la pregunta se dio de alta exitosamente
      if (is_numeric($res)){
        //si la pregunta debe tener opciones
        if ($this->Pregunta->tipo == TIPO_SELECCION_SIMPLE){
          $this->Pregunta->idPregunta = (int)$res; //id de la nueva pregunta
          foreach ($datosOpciones as $opcion) {
            $res = $this->Pregunta->altaOpcion($opcion);
            if (!is_numeric($res)){
              $this->gp->baja($this->Pregunta->idPregunta);//si hay error borro la pregunta con las opciones
              break;
            }
          }
        }
      }
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('preguntas/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    $this->data['pregunta'] = &$this->Pregunta;
    $this->data['opciones'] = ($datosOpciones)?$datosOpciones:array();
    $this->data['disabled'] = false; //permitir al usuario editar las opciones de la pregunta
    $this->data['tituloFormulario'] = 'Nueva Pregunta';
    $this->data['urlFormulario'] = site_url('preguntas/nueva');
    $this->load->view('editar_pregunta', $this->data);
  }
  
  /*
   * Recepción del formulario para eliminar una pregunta
   * POST: idPregunta
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
      redirect('preguntas/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idPregunta','Pregunta','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_preguntas','gp');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gp->baja((int)$this->input->post('idPregunta'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La pregunta se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('preguntas/listar');
  }
  
  /*
   * Editar o reformular preguntas (solamente texto y descripcion)
   * POST: idPregunta, texto, descripcion
   */
  public function modificar($idPregunta=null){
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('preguntas/listar');
    }
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');

    //leer datos del post
    $this->Pregunta->idPregunta = (int)$this->input->post('idPregunta');
    $this->Pregunta->texto = $this->input->post('texto', TRUE);
    $this->Pregunta->descripcion = ($this->input->post('descripcion'))?$this->input->post('descripcion', TRUE):NULL;

    //verificar los datos
    $this->form_validation->set_rules('idPregunta','Pregunta','is_natural_no_zero|required');
    $this->form_validation->set_rules('texto','Texto','max_length[250]|required');
    $this->form_validation->set_rules('descripcion','Descripción','max_length[250]');
    if($this->form_validation->run()){
      $res = $this->gp->modificar($this->Pregunta->idPregunta, $this->Pregunta->texto, $this->Pregunta->descripcion);
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('preguntas/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if (!$idPregunta) redirect('preguntas/nueva');
    $this->Pregunta = $this->gp->dame((int)$idPregunta);
    if (!$this->Pregunta){
      $this->session->set_flashdata('resultadoOperacion', "No existe la pregunta seleccionada.");
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      redirect('preguntas/listar');
    }
    $this->data['pregunta'] = &$this->Pregunta;
    $this->data['opciones'] = array();
    $this->data['disabled'] = true; //impedir que el usuario edite las opciones de la pregunta
    $this->data['tituloFormulario'] = 'Editar Pregunta';
    $this->data['urlFormulario'] = site_url('preguntas/modificar');
    $this->load->view('editar_pregunta', $this->data);
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    //if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $this->load->model('Pregunta');
      $this->load->model('Gestor_preguntas','gp');
      $preguntas = $this->gp->buscar($this->input->post('buscar'));
      echo "\n";
      foreach ($preguntas as $pregunta) {
        echo  "$pregunta->idPregunta\t".
              "$pregunta->texto\t".
              "$pregunta->creacion\t".
              "$pregunta->tipo\t\n";
      }
    }
  }
}
?>