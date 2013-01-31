<?php

/**
 * 
 */
class Departamentos extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //datos de session para enviarse a las vistas
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row(); 
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); 
  }
  
  public function index(){
    $this->listar();
  }

  /*
   * Muestra el listado de departamentos.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');

    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($pagInicio, self::per_page);
    $tabla = array(); //datos para mandar a la vista
    foreach ($departamentos as $i => $departamento) {
      $tabla[$i]['idDepartamento'] = $departamento->idDepartamento;
      $tabla[$i]['nombre'] = $departamento->nombre;
      //agrego datos del jefe de departamento (si existe)
      if ($departamento->idJefeDepartamento){
        $jefe = $this->ion_auth->user($departamento->idJefeDepartamento)->row();
        $tabla[$i]['jefeDepartamento'] = array(
          'id' => $jefe->id,
          'apellido' => $jefe->apellido,
          'nombre' => $jefe->nombre
        );
      }
      else {
        $tabla[$i]['jefeDepartamento'] = array(
          'id' => null,
          'apellido' => '',
          'nombre' => ''
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
    $this->data['tabla'] = &$tabla; //array de datos de los Departamentos
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_departamentos', $this->data);
  }

  /*
   * Ver y editar datos relacionados a un departamento
   */
  public function ver($idDepartamento=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idDepartamento = (int)$idDepartamento;
    
    //cargo modelos, librerias, etc.
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo datos del departamento
    $departamento = $this->gd->dame($idDepartamento);
    if ($departamento){
      $this->data['departamento'] = array(
        'idDepartamento' => $departamento->idDepartamento,
        'idJefeDepartamento' => $departamento->idJefeDepartamento,
        'nombre' => $departamento->nombre
      );
      //agrego datos del jefe de departamento (si existe)
      if ($departamento->idJefeDepartamento){
        $jefe = $this->ion_auth->user($departamento->idJefeDepartamento)->row();
        $this->data['departamento']['jefeDepartamento'] = array(
          'id' => $jefe->id,
          'apellido' => $jefe->apellido,
          'nombre' => $jefe->nombre
        );
      }
      else {
        $this->data['departamento']['jefeDepartamento'] = array(
          'id' => '',
          'apellido' => '',
          'nombre' => ''
        );
      }
      //envio datos a la vista
      $this->load->view('ver_departamento', $this->data);
    }
    else{
      show_error('El Identificador de Departamento no es válido.');
    }
  }
  
  /*
   * Recepción del formulario para agregar nuevo departamento
   * POST: nombre
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');
      
      //agrego departamento y cargo vista para mostrar resultado
      $idJefeDepartamento = $this->input->post('idJefeDepartamento',TRUE);
      $res = $this->gd->alta(($idJefeDepartamento=='')?NULL:$idJefeDepartamento, $this->input->post('nombre',TRUE));
      $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":$res;
      $this->data['link'] = site_url("departamentos/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
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
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');
      $idDepartamento = $this->input->post('idDepartamento',TRUE);
      $idJefeDepartamento = $this->input->post('idJefeDepartamento',TRUE);
      
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gd->modificar($idDepartamento, ($idJefeDepartamento=='')?NULL:$idJefeDepartamento, $this->input->post('nombre',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("departamentos/ver/$idDepartamento"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina del departamento
      $this->ver($this->input->post('idDepartamento',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar un departamento
   * POST: idDepartamento
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gd->baja($this->input->post('idDepartamento',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("departamentos/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: idDepartamento
   */
  public function buscarAjax(){
    $buscar = $this->input->post('buscar');
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamentos = $this->gd->buscar($buscar);
    echo "\n";
    foreach ($departamentos as $departamento) {
      echo  "$departamento->idDepartamento\t".
            "$departamento->nombre\t\n";
    }
  }
  
}

?>