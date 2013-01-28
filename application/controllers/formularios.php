<?php

/**
 * 
 */
class Formularios extends CI_Controller{
    
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }

  public function index(){  
    $this->listar();
  }
  
  /*
   * Muestra el listado de formularios
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
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    
    //obtengo lista de formularios
    $formularios = $this->gf->listar($PagInicio, self::per_page);

    $tabla = array(); //datos para mandar a la vista
    foreach ($formularios as $i => $formulario) {
      $tabla[$i]['IdFormulario'] = $formulario->IdFormulario;
      $tabla[$i]['Nombre'] = $formulario->Nombre;
      $tabla[$i]['Titulo'] = $formulario->Titulo;
      $tabla[$i]['Creacion'] = $formulario->Creacion;
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("formularios/listar");
    $config['total_rows'] = $this->gf->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('lista_formularios', $data);
  }

  /*
   * Muestra el formulario de edicion de formularios
   */
  public function editar(){
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('editar_formulario', $data);
  }
  
  /*
   * Recepción del formulario para eliminar un formulario
   * POST: IdFormulario
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdFormulario','ID Formulario','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Gestor_formularios','gf');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gf->baja($this->input->post('IdFormulario',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("formularios"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  //funcion para responder solicitudes AJAX
  public function buscarAJAX(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->buscar($buscar);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->IdFormulario\t".
            "$formulario->Nombre\t".
            "$formulario->Creacion\t\n";
    }
  }
  
  //funcion para responder solicitudes AJAX
  public function listarAJAX(){
    //VERIFICAR
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->listar(0,1000);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->IdFormulario\t".
            "$formulario->Nombre\t".
            "$formulario->Creacion\t\n";
    }
  }
}

?>