<?php

/**
 * 
 */
class Departamentos extends CI_Controller{
  
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de departamentos.
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
    $this->load->model('Persona');
    $this->load->model('Departamento');
    $this->load->model('Gestor_personas','gp');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($PagInicio, self::per_page);
    $tabla = array(); //datos para mandar a la vista
    foreach ($departamentos as $i => $departamento) {
      $tabla[$i]['IdDepartamento'] = $departamento->IdDepartamento;
      $tabla[$i]['Nombre'] = $departamento->Nombre;
      //agrego datos del jefe de departamento (si existe)
      $jefe = $this->gp->dame($departamento->IdJefeDepartamento);
      if ($jefe){
        $tabla[$i]['JefeDepartamento'] = array(
          'IdPersona' => $jefe->IdPersona,
          'Apellido' => $jefe->Apellido,
          'Nombre' => $jefe->Nombre
        );
      }
      else {
        $tabla[$i]['JefeDepartamento'] = array(
          'IdPersona' => null,
          'Apellido' => '',
          'Nombre' => ''
        );
      }
    }

    //genero la lista de links de paginación
    $config['base_url'] = site_url("departamentos/listar");
    $config['total_rows'] = $this->gd->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('lista_departamentos', $data);
  }

  /*
   * Ver y editar datos relacionados a un departamento
   */
  public function ver($IdDepartamento=null, $PagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    $IdDepartamento = (int)$IdDepartamento;
    
    //cargo modelos, librerias, etc.
    $this->load->model('Persona');
    $this->load->model('Departamento');
    $this->load->model('Gestor_personas','gp');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo datos del departamento
    $departamento = $this->gd->dame($IdDepartamento);
    if ($departamento){
      $data['departamento'] = array(
        'IdDepartamento' => $departamento->IdDepartamento,
        'Nombre' => $departamento->Nombre
      );
      //agrego datos del jefe de departamento (si existe)
      $jefe = $this->gp->dame($departamento->IdJefeDepartamento);
      if ($jefe){
        $data['departamento']['JefeDepartamento'] = array(
          'IdPersona' => $jefe->IdPersona,
          'Apellido' => $jefe->Apellido,
          'Nombre' => $jefe->Nombre
        );
      }
      else {
        $data['departamento']['JefeDepartamento'] = array(
          'IdPersona' => 0,
          'Apellido' => '',
          'Nombre' => ''
        );
      }
      //envio datos a la vista
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $this->load->view('ver_departamento', $data);
    }
    else{
      show_error('El Identificador de Departamento no es válido.');
    }
  }
  
  /*
   * Recepción del formulario para agregar nuevo departamento
   * POST: Nombre
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');
      
      //agrego departamento y cargo vista para mostrar resultado
      $IdJefeDepartamento = $this->input->post('IdJefeDepartamento',TRUE);
      $res = $this->gd->alta(($IdJefeDepartamento=='')?NULL:$IdJefeDepartamento, $this->input->post('Nombre',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":$res;
      $data['link'] = site_url("departamentos"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de un departamento
   * POST: IdDepartamento, IdJefeDepartamento, Nombre
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');
      $IdDepartamento = $this->input->post('IdDepartamento',TRUE);
      $IdJefeDepartamento = $this->input->post('IdJefeDepartamento',TRUE);
      
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gd->modificar($IdDepartamento, ($IdJefeDepartamento=='')?NULL:$IdJefeDepartamento, $this->input->post('Nombre',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("departamentos/ver/$IdDepartamento"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina del departamento
      $this->ver($this->input->post('IdDepartamento',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar un departamento
   * POST: IdDepartamento
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_departamentos','gd');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gd->baja($this->input->post('IdDepartamento',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("departamentos"); //link para boton aceptar/continuar
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
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamentos = $this->gd->buscar($buscar);
    echo "\n";
    foreach ($departamentos as $departamento) {
      echo  "$departamento->IdDepartamento\t".
            "$departamento->Nombre\t\n";
    }
  }
  
}

?>