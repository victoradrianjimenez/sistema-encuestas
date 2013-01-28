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