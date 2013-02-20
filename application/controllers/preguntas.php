<?php

/**
 * 
 */
class Preguntas extends CI_Controller {
  
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
   * Muestra el listado de preguntas.
   * Última revisión: 2012-02-04 12:18 p.m.
   */
  public function listar($PagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Pregunta');
    $this->load->model('Carrera');
    $this->load->model('Gestor_preguntas','gp');
    $this->load->model('Gestor_carreras','gc');
    
    //obtengo lista de preguntas
    $preguntas = $this->gp->listar($PagInicio, self::per_page);
    $lista = array(); //datos para mandar a la vista
    foreach ($preguntas as $i => $pregunta) {
      $carrera = ($pregunta->idCarrera!='')?$this->gc->dame($pregunta->idCarrera):FALSE;
      $lista[$i] = array(
        'pregunta' => $pregunta,
        'carrera' => ($carrera)?$carrera:$this->Carrera
      );
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("preguntas/listar"),
      'total_rows' => $this->gp->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Departamentos
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_preguntas', $this->data);
  }

  /*
   * Recepción del formulario para agregar nueva pregunta
   * POST: texto, descripcion, tipo, obligatoria, ordenInverso, unidad, limiteInferior, limiteSuperior, textoOpcion_###
   * Última revisión: 2012-02-04 12:26 p.m.
   */
  public function nueva(){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    if ($this->input->post('submit')){
      //cargo modelos y librerias necesarias
      $this->load->model('Opcion');
      $this->load->model('Pregunta');
      $this->load->model('Gestor_preguntas','gp');
      //verifico si el usuario tiene permisos para continuar
      if (!$this->ion_auth->is_admin()){
        show_error('No tiene permisos para realizar esta operación.');
        return;
      }
      $error = false;
      //leo los datos enviados por post, y los almaceno en un array
      $entradas = $this->input->post(NULL, TRUE);
      $datosOpciones = array();
      foreach ($entradas as $key => $x) {
        if (strpos($key, 'textoOpcion') !== false) {
          sscanf($key, "textoOpcion_%d",$i);
          $datosOpciones[$i] = $x;
        }
      }
      //CHEQUEAR!!
      $this->Pregunta->texto = $this->input->post('texto', TRUE);
      $tmp =  $this->input->post('descripcion', TRUE);
      $this->Pregunta->descripcion = ($tmp)?$tmp:NULL;
      $this->Pregunta->tipo = $this->input->post('tipo', TRUE);
      $tmp = $this->input->post('obligatoria', TRUE);
      $this->Pregunta->obligatoria = ((bool)$tmp)?'S':'N';
      $tmp = $this->input->post('ordenInverso', TRUE);
      $this->Pregunta->ordenInverso = ((bool)$tmp)?'S':'N';
      $tmp = $this->input->post('unidad', TRUE);
      $this->Pregunta->unidad = ($tmp)?$tmp:NULL;
      if ($this->Pregunta->tipo == 'N'){
        $this->Pregunta->limiteInferior = $this->input->post('limiteInferior', TRUE);
        $this->Pregunta->limiteSuperior = $this->input->post('limiteSuperior', TRUE);
        $this->Pregunta->paso = $this->input->post('paso', TRUE);
      }
      else {
        $this->Pregunta->limiteInferior = NULL;
        $this->Pregunta->limiteSuperior = NULL;
        $this->Pregunta->paso = NULL;
      }
      $res = $this->gp->alta(NULL, $this->Pregunta->texto, $this->Pregunta->descripcion, $this->Pregunta->tipo, $this->Pregunta->obligatoria, 
                            $this->Pregunta->ordenInverso, $this->Pregunta->limiteInferior, $this->Pregunta->limiteSuperior, $this->Pregunta->paso, $this->Pregunta->unidad);
      if (is_numeric($res)){
        $this->Pregunta->idPregunta = (int)$res;
        foreach ($datosOpciones as $i => $opcion) {
          $res = $this->Pregunta->altaOpcion($opcion);
          if (!is_numeric($res)){
            $error = true;
            break;
          }
        }
      }
      else{
        $error = true;
      }
      if($error){
        //borro la pregunta con las opciones dadas de alta hasta que se produjo el error
        $this->gp->baja($this->Pregunta->idPregunta);
      }
      //cargo vista para mostrar resultado
      $this->data['mensaje'] = (!$error)?"La operación se realizó con éxito. El ID del nuevo formulario es ".$this->Pregunta->idPregunta.".":$res;
      $this->data['link'] = site_url("preguntas/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else {
      //muestro el formulario para crear pregunta
      $this->load->view('editar_pregunta', $this->data);
    }
  }
  
  /*
   * Recepción del formulario para eliminar una pregunta
   * POST: idPregunta
   * Última revisión: 2012-02-04 04:03 p.m.
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idPregunta','Pregunta','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_preguntas','gp');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gp->baja($this->input->post('idPregunta',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("preguntas/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   * Última revisión: 2012-02-04 04:05 p.m.
   */
  public function buscarAjax(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $this->load->model('Pregunta');
      $this->load->model('Gestor_preguntas','gp');
      $preguntas = $this->gp->buscar($this->input->post('buscar'));
      echo "\n";
      foreach ($preguntas as $pregunta) {
        echo  "$pregunta->idPregunta\t".
              "$pregunta->idCarrera\t".
              "$pregunta->texto\t".
              "$pregunta->creacion\t".
              "$pregunta->tipo\t".
              "$pregunta->obligatoria\t\n";
      }
    }
  }
  
}

?>