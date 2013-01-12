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
  
}

?>