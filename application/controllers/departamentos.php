<?php

/**
 * 
 */
class Departamentos extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  
  public function index(){
    $this->listar();
  }
  
  
  public function listar($pagInicio=0){
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
       
    //genero la lista de links de paginación
    $config['base_url'] = site_url("departamentos/listar");
    $config['total_rows'] = $this->gd->cantidad();
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($pagInicio, $config['per_page']);
    $tabla = array();
    foreach ($departamentos as $i => $departamento) {
      $tabla[$i]['IdDepartamento'] = $departamento->IdDepartamento;
      $tabla[$i]['Nombre'] = $departamento->Nombre;
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
    $this->load->view('lista_departamentos', $data);
  }


  /*
   * Ver todo lo relacionado a un departamento
   */
  public function ver($idDepartamento=0, $pagInicio=0){
    if (!is_numeric($idDepartamento) || $idDepartamento<1){
      show_error('El Identificador de Departamento no es válido.');
      return;
    }
    elseif (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamento = $this->gd->dame($idDepartamento);
    if ($departamento != FALSE){
      $data['departamento'] = array(
        'IdDepartamento' => $departamento->IdDepartamento,
        'IdJefeDepartamento' => $departamento->IdJefeDepartamento,
        'Nombre' => $departamento->Nombre
      );
      //envio datos a la vista
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $this->load->view('ver_departamento', $data);
    }
    else{
      show_error('El Identificador de Departamento no es válido.');
    }
  }
  
  /*
   * Agregar nuevo departamento
   * POST: Nombre
   */
  public function nuevo(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $this->form_validation->set_rules('Nombre','Nombre','required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_departamentos','gd');
      //agrego departamento y cargo vista para mostrar resultado
      $res = $this->gd->alta($this->input->post('Nombre',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":$res;
      $data['link'] = site_url("departamentos/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Modificar los datos de una carrera
   * POST: IdCarrera, IdDepartamento, Nombre, Plan
   */
  public function modificar(){ //PASAR DATOS POR POST!!!!
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdDepartamento = $this->input->post('IdDepartamento',TRUE);
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Departamento');
      $this->load->model('Gestor_departamentos','gd');
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gd->modificar($IdDepartamento, $this->input->post('Nombre',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("departamentos/ver/$IdDepartamento"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($IdDepartamento);
    }
  }

  /*
   * Eliminar un departamento
   * POST: IdDepartamento
   */
  public function eliminar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdDepartamento = $this->input->post('IdDepartamento',TRUE);
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_departamentos','gd');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gd->baja($IdDepartamento);
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
  public function listarAjax(){
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamentos = $this->gd->listar(0, 1000);
    foreach ($departamentos as $departamento) {
      echo  "$departamento->IdDepartamento\t".
            "$departamento->Nombre\t\n";
    }
  }
  
}

?>