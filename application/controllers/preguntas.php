<?php

/**
 * 
 */
class Preguntas extends CI_Controller {
  
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
	
	function __construct() {
	  parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
	}
  
  public function index(){
    $this->listar();
  }
  
  
  /*
   * Muestra el listado de preguntas.
   */
  public function listar($PagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    
    //obtengo lista de departamentos
    $preguntas = $this->gp->listar($PagInicio, self::per_page);
    $tabla = array(); //datos para mandar a la vista
    foreach ($preguntas as $i => $pregunta) {
      $tabla[$i] = array(
        'IdPregunta' => $pregunta->IdPregunta,
        'Texto' => $pregunta->Texto,
        'Creacion' => $pregunta->Creacion,
        'Tipo' => $pregunta->Tipo,
        'Obligatoria' => $pregunta->Obligatoria
      );
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("preguntas/listar");
    $config['total_rows'] = $this->gp->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de las preguntas
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('lista_preguntas', $data);
  }

  /*
   * Muestra el formulario de edicion de la pregunta
   */
  public function editar(){
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('editar_pregunta', $data);
  }
  
  /*
   * Recepción del formulario para agregar nueva pregunta
   * POST: Texto, Descripcion, Tipo, Obligatoria, OrdenInverso, Unidad, LimiteInferior, LimiteSuperior, TextoOpcion_###
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //cargo modelos y librerias necesarias
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    
    //leo los datos enviados por post, y los almaceno en un array
    $entradas = $this->input->post(NULL, TRUE);
    $datosOpciones = array();
    foreach ($entradas as $key => $x) {
      if (strpos($key, 'TextoOpcion') !== false) {
        sscanf($key, "TextoOpcion_%d",$i);
        $datosOpciones[$i] = $x;
      }
    }

    //CHEQUEAR!!
    $error = false;
    
    $this->Pregunta->Texto = $this->input->post('Texto', TRUE);
    $tmp =  $this->input->post('Descripcion', TRUE);
    $this->Pregunta->Descripcion = ($tmp)?$tmp:NULL;
    $this->Pregunta->Tipo = $this->input->post('Tipo', TRUE);
    $tmp = $this->input->post('Obligatoria', TRUE);
    $this->Pregunta->Obligatoria = ((bool)$tmp)?'S':'N';
    $tmp = $this->input->post('OrdenInverso', TRUE);
    $this->Pregunta->OrdenInverso = ((bool)$tmp)?'S':'N';
    $tmp = $this->input->post('Unidad', TRUE);
    $this->Pregunta->Unidad = ($tmp)?$tmp:NULL;
    if ($this->Pregunta->Tipo == 'N'){
      $this->Pregunta->LimiteInferior = $this->input->post('LimiteInferior', TRUE);
      $this->Pregunta->LimiteSuperior = $this->input->post('LimiteSuperior', TRUE);
      $this->Pregunta->Paso = $this->input->post('Paso', TRUE);
    }
    else {
      $this->Pregunta->LimiteInferior = NULL;
      $this->Pregunta->LimiteSuperior = NULL;
      $this->Pregunta->Paso = NULL;
    }
    $res = $this->gp->alta(NULL, $this->Pregunta->Texto, $this->Pregunta->Descripcion, 
                          $this->Pregunta->Tipo, $this->Pregunta->Obligatoria, 
                          $this->Pregunta->OrdenInverso, $this->Pregunta->LimiteInferior,
                          $this->Pregunta->LimiteSuperior, $this->Pregunta->Paso,
                          $this->Pregunta->Unidad);
    if (!is_numeric($res)){
      $error = true;
    }
    else{
      $this->Pregunta->IdPregunta = (int)$res;
      foreach ($datosOpciones as $i => $opcion) {
        $res = $this->Pregunta->altaOpcion($opcion);
        if (!is_numeric($res)){
          $error = true;
          break;
        }
      }
    }
    if($error){
      $this->gp->baja($this->Pregunta->IdPregunta);
    }
    //cargo vista para mostrar resultado
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $data['mensaje'] = (!$error)?"La operación se realizó con éxito. El ID del nuevo formulario es ".$this->Pregunta->IdPregunta.".":$res;
    $data['link'] = site_url("preguntas"); //hacia donde redirigirse
    $this->load->view('resultado_operacion', $data);
  }
  
  /*
   * Recepción del formulario para eliminar una pregunta
   * POST: IdPregunta
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdPregunta','ID Pregunta','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Gestor_preguntas','gp');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gp->baja($this->input->post('IdPregunta',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("preguntas"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }
  
  
  //funcion para responder solicitudes AJAX
  public function buscarAjax(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $preguntas = $this->gp->buscar($buscar);
    echo "\n";
    foreach ($preguntas as $pregunta) {
      echo  "$pregunta->IdPregunta\t".
            "$pregunta->IdCarrera\t".
            "$pregunta->Texto\t".
            "$pregunta->Creacion\t".
            "$pregunta->Tipo\t".
            "$pregunta->Obligatoria\t\n";
    }
  }
  
  
}

?>