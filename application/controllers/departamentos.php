<?php

/**
 * 
 */
class Departamentos extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    $this->data['csrf'] = array();
  }
  
  public function index(){
    $this->listar();
  }

  /*
   * Muestra el listado de departamentos.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');

    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($pagInicio, PER_PAGE);
    $lista = array(); //datos para mandar a la vista
    foreach ($departamentos as $i => $departamento) {
      $jefe = $this->gu->dame($departamento->idJefeDepartamento);
      $lista[$i] = array(
        'departamento' => $departamento,
        'jefeDepartamento' => ($jefe)?$jefe:$this->Usuario
      );
    }
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("departamentos/listar"),
      'total_rows' => $this->gd->cantidad()
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Departamentos
    $this->data['departamento'] = &$this->Departamento; //datos por defecto de un nuevo departamento
    $this->data['jefeDepartamento'] = &$this->Usuario; //datos por defecto de un nuevo departamento
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_departamentos', $this->data);
  }
  
  /*
   * Permite crear un nuevo departamento
   * POST: nombre, idJefeDepartamento
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
    }
    
    //verifico datos POST
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');      
    if($this->form_validation->run() && $this->_valid_csrf_nonce() === TRUE){
      $this->load->model('Gestor_departamentos','gd');
      //agrego departamento y muestro mensaje resultado
      $idJefeDepartamento = $this->input->post('idJefeDepartamento',TRUE);
      $res = $this->gd->alta(($idJefeDepartamento=='')?NULL:$idJefeDepartamento, $this->input->post('nombre',TRUE));
      $this->data['resultadoOperacion'] = (is_numeric($res))?"El nuevo departamento se agregó con éxito (el ID del nuevo departamento es $res).":$res;
      $this->listar();
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->load->model('Usuario');
      $this->load->model('Departamento');
      $this->data['departamento'] = &$this->Departamento; //datos por defecto de un nuevo departamento
      $this->data['jefeDepartamento'] = &$this->Usuario;
      $this->data['tituloFormulario'] = 'Nuevo Departamento';
      $this->data['urlFormulario'] = site_url('departamentos/nuevo');
      $this->data['csrf'] = $this->_get_csrf_nonce(); //codigo de uso unico
      $this->load->view('editar_departamento', $this->data);

    }
  }

  /*
   * Modificar los datos de un departamento
   * POST: idDepartamento, idJefeDepartamento, nombre
   */
  public function modificar($idDepartamento=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');

    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required'); 
    if($this->form_validation->run()){
      $idDepartamento = (int)$this->input->post('idDepartamento');
      $idJefeDepartamento = $this->input->post('idJefeDepartamento',TRUE);
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gd->modificar($idDepartamento, ($idJefeDepartamento=='')?NULL:$idJefeDepartamento, $this->input->post('nombre',TRUE));
      $this->data['resultadoOperacion'] = (strcmp($res, 'ok')==0)?'La modificación del departamento se realizó con éxito.':$res;
      $this->listar();
    }
    else{
      //obtengo datos del departamento
      $departamento = $this->gd->dame((int)$idDepartamento);
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->load->model('Usuario');
      $this->load->model('Departamento');
      $this->data['departamento'] = ($departamento)?$departamento:$this->Departamento;
      $this->data['jefeDepartamento'] = &$this->Usuario;
      $this->data['tituloFormulario'] = 'Modificar Departamento';
      $this->data['urlFormulario'] = site_url('departamentos/modificar');
      $this->load->view('editar_departamento', $this->data);
    }
  }

  /*
   * Recepción del formulario para eliminar un departamento
   * POST: idDepartamento
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    if($this->form_validation->run() && $this->_valid_csrf_nonce() === TRUE){
      $this->load->model('Gestor_departamentos','gd');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gd->baja($this->input->post('idDepartamento',TRUE));
      $this->data['resultadoOperacion'] = (strcmp($res, 'ok')==0)?'El departamento se eliminó con éxito.':$res;
      $this->data['csrf'] = $this->_get_csrf_nonce(); //codigo de uso unico
    }
    $this->listar();
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAjax(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar', TRUE);
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
  
  /*
   * crear clave de uso unico
   */  
  private function _get_csrf_nonce(){
    $this->load->helper('string');
    $key   = random_string('alnum', 8);
    $value = random_string('alnum', 20);
    $this->session->set_flashdata('csrfkey', $key);
    $this->session->set_flashdata('csrfvalue', $value);
    return array($key, $value);
  }

  /*
   * Verificar clave de uso unico
   */
  private function _valid_csrf_nonce(){
    if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
        $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')){
      return TRUE;
    }
    return FALSE;
  }
}

?>