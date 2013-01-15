<?php

/**
 * 
 */
class Departamentos extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    $this->listar();
  }
  
  public function listar($pagina=0){
    if (!is_numeric($pagina)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
       
    //genero la lista de links de paginación
    $config['base_url'] = site_url('departamentos/listar/');
    $config['total_rows'] = $this->gd->cantidad();
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($pagina, $config['per_page']);
    $tabla = array();
    foreach ($departamentos as $i => $departamento) {
      $tabla[$i]['idDepartamento'] = $departamento->IdDepartamento;
      $tabla[$i]['nombre'] = $departamento->Nombre;
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_departamentos', $data);
  }
  
  
  public function nuevo(){
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!

    //si no recibimos ningún valor proveniente del formulario
    if(!$this->input->post('submit')){
      $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
      $data['departamento'] = array('nombre' => ''); //datos del departamento
      $data['link'] = site_url('departamentos/nuevo'); //hacia donde mandar los datos      
      $this->load->view('editar_departamento',$data); 
    }
    else{
      //verifico si los datos son correctos
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
      if($this->form_validation->run()==FALSE){
        //en caso de que los datos sean incorrectos, cargo el formulario nuevamente
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['departamento'] = array('nombre' => $this->input->post('nombre')); //datos del departamento
        $data['link'] = site_url('departamentos/nuevo'); //hacia donde mandar los datos
        $this->load->view('editar_departamento',$data);
      }
      else{
        //agrego departamento y cargo vista para mostrar resultado
        $this->load->model('Gestor_departamentos','gd');
        $res = $this->gd->alta($this->input->post('nombre',TRUE));
        $data['usuario'] = unserialize($this->session->userdata('usuario')); //datos de session
        $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID del nuevo departamento es $res.":$res;
        $data['link'] = site_url('departamentos'); //hacia donde redirigirse
        $this->load->view('resultado_operacion', $data);
      }
    }
  }
}

?>