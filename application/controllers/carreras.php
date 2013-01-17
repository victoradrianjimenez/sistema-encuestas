<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  private function _datosDepartamentos(){
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamentos = $this->gd->listar(0, 255);
    $datos_departamentos = array();
    foreach ($departamentos as $i => $departamento) {
      $datos_departamentos[$i] = array(
        'idDepartamento' => $departamento->IdDepartamento,
        'nombre' => $departamento->Nombre);
    }
    return $datos_departamentos;
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
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
       
    //genero la lista de links de paginación
    $config['base_url'] = "carreras/listar/";
    $config['total_rows'] = $this->gc->cantidad();
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de carreras
    $carreras = $this->gc->listar($pagInicio, $config['per_page']);
    $tabla = array();
    foreach ($carreras as $i => $carrera) {
      $tabla[$i]['idCarrera'] = $carrera->IdCarrera;
      $tabla[$i]['nombre'] = $carrera->Nombre;
      $tabla[$i]['plan'] = $carrera->Plan;
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de las Carreras
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_carreras', $data);
  }


  public function nueva(){
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!

    //si no recibimos ningún valor proveniente del formulario
    if(!$this->input->post('submit')){
      $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
      $data['departamentos'] = $this->_datosDepartamentos();
      $data['carrera'] = array(
        'idCarrera' => 0,
        'idDepartamento' => 0,
        'nombre' => '',
        'plan' => date('Y'));
      $data['link'] = "carreras/nueva"; //hacia donde mandar los datos      
      $this->load->view('editar_carrera',$data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('idDepartamento','ID Departamento','is_natural_no_zero');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('plan','Plan','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
      $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos, cargo el formulario nuevamente
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['departamentos'] = $this->_datosDepartamentos();
        $data['carrera'] = array(
          'idCarrera' => 0,
          'idDepartamento' => 0,
          'plan' => $this->input->post('plan'),
          'nombre' => $this->input->post('nombre'));
        $data['link'] = "carreras/nueva"; //hacia donde mandar los datos
        $this->load->view('editar_carrera',$data);
      }
      else{
        //agrego carrera y cargo vista para mostrar resultado
        $this->load->model('Gestor_carreras','gc');
        $res = $this->gc->alta($this->input->post('idDepartamento',TRUE), $this->input->post('nombre',TRUE),$this->input->post('plan',TRUE));
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
        $data['link'] = "carreras"; //hacia donde redirigirse
        $this->load->view('resultado_operacion', $data);
      }
    }
  }


  public function eliminar($IdCarrera=0){ //PASAR DATOS POR POST!!!!
    if (!is_numeric($IdCarrera)){
      show_error('El ID Carrera es inválido.');
      return;
    }

    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!

    //doy de baja y cargo vista para mostrar resultado
    $this->load->model('Gestor_carreras','gc');
    $res = $this->gc->baja($IdCarrera);
    $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
    $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
    $data['link'] = "carreras"; //link para boton aceptar/continuar
    $this->load->view('resultado_operacion', $data);
  }


  public function modificar($IdCarrera=0){ //PASAR DATOS POR POST!!!!
    if (!is_numeric($IdCarrera)){
      show_error('El ID Carrera es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    
    //si no recibimos ningún valor proveniente del formulario
    if(!$this->input->post('submit')){
      //si el departamento no existe mostrar mensaje
      $carrera = $this->gc->dame($IdCarrera);
      if ($carrera != FALSE){
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['departamentos'] = $this->_datosDepartamentos();
        $data['carrera'] = array(
          'idCarrera' => $carrera->IdCarrera,
          'idDepartamento' => $carrera->IdDepartamento,
          'nombre' => $carrera->Nombre,
          'plan' => $carrera->Plan);
        $data['link'] = "carreras/modificar"; //hacia donde mandar los datos      
        $this->load->view('editar_carrera',$data); 
      }
      else{
        show_error('El ID Carrera es inválido.');
      }
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('idDepartamento','ID Departamento','is_natural_no_zero');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('plan','Plan','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
      $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos, cargo el formulario nuevamente
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['departamentos'] = $this->_datosDepartamentos();
        $data['carrera'] = array(
          'idCarrera' => $this->input->post('idCarrera'),
          'idDepartamento' => $this->input->post('idDepartamento'),
          'plan' => $this->input->post('plan'),
          'nombre' => $this->input->post('nombre'));
        $data['link'] = "carreras/modificar"; //hacia donde mandar los datos
        $this->load->view('editar_carrera',$data);
      }
      else{
        //agrego carrera y cargo vista para mostrar resultado
        $this->load->model('Gestor_carreras','gc');
        $res = $this->gc->modificar($this->input->post('idCarrera',TRUE),$this->input->post('idDepartamento',TRUE), $this->input->post('nombre',TRUE),$this->input->post('plan',TRUE));
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
        $data['link'] = "carreras"; //hacia donde redirigirse
        $this->load->view('resultado_operacion', $data);
      }
    }
  }


  public function listarDepartamento($idDepartamento=null, $pagina=0){
    if ($idDepartamento != null && !is_numeric($idDepartamento)){
      show_error('El Identificador de Departamento no es válido.');
      return;
    }
    elseif (!is_numeric($pagina)){
      show_error('El número de página es inválido.');
      return;
    }

    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Departamento');
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo lista de carreras pertenecientes al departamento
    $departamento = $this->gd->dame($idDepartamento);
    if ($departamento != FALSE){ //objeto departamento;
      //genero la lista de links de paginación
      $config['base_url'] = "carreras/listardepartamento/$idDepartamento/";
      $config['total_rows'] = $departamento->cantidadCarreras();
      $config['per_page'] = 5;
      $config['uri_segment'] = 3;
      $this->pagination->initialize($config);
      
      //obtengo lista de carreras
      $carreras = $departamento->listarCarreras($pagina, $config['per_page']);
      $tabla = array();
      foreach ($carreras as $i => $carrera) {
        $tabla[$i]=array(
          'idCarrera' => $carrera->IdCarrera,
          'nombre' => $carrera->Nombre,
          'plan' => $carrera->Plan);
      }

      //envio datos a la vista
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
      $data['departamento'] = array('nombre' => $departamento->Nombre); //array de datos del departamento
      $data['tabla'] = $tabla; //array de datos de las Carrras
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->load->view('lista_carreras', $data);
    }
    else{
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
      $this->load->view('index',$data);
    }
  }

}

?>