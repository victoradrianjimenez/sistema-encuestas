<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    
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
       
    //genero la lista de links de paginación
    $config['base_url'] = site_url("carreras/listardepartamento/$idDepartamento/");
    $config['total_rows'] = $this->gd->cantidad();
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    
    //obtengo lista de carreras pertenecientes al departamento
    $departamento = $this->gd->dame($idDepartamento);
    if ($departamento != FALSE){ //objeto departamento;
      $carreras = $departamento->listarCarreras($pagina, $config['per_page']);
      $tabla = array();
      foreach ($carreras as $i => $carrera) {
        $tabla[$i]['idCarrera'] = $carrera->IdCarrera;
        $tabla[$i]['nombre'] = $carrera->Nombre;
        $tabla[$i]['plan'] = $carrera->Plan;
      }

      //envio datos a la vista
      $data['departamento'] = array('nombre' => $departamento->Nombre); //array de datos del departamento
      $data['tabla'] = $tabla; //array de datos de las Carrras
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->load->view('lista_carreras', $data);
    }
    else{
      $this->load->view('index',$data);
    }
  }

}

?>