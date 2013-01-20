<?php

/**
 * 
 */
class Materias extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    $this->listar();
  }
  
    
  public function listar($idCarrera=0, $pagInicio=0){
    if (!is_numeric($idCarrera)){
      show_error('El Identificador de Carrera no es válido.');
      return;
    }
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    if ($idCarrera == 0){
      $cantidadMaterias = $this->gm->cantidad();
      $materias = $this->gm->listar($pagInicio, 5);
    }
    else{
      $carrera = $this->gc->dame($idCarrera);
      if ($carrera != FALSE){
        $cantidadMaterias = $carrera->cantidadMaterias();
        $materias = $carrera->listarMaterias($pagInicio, 5);
        $data['carrera'] = array(
          'Nombre' => $carrera->Nombre,
          'Plan' => $carrera->Plan
        );
      }
      else{
        show_error('El Identificador de Carrera no es válido.');
        return;
      }
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("materias/listar/$idCarrera");
    $config['total_rows'] = $cantidadMaterias;
    $config['per_page'] = 5;
    $config['uri_segment'] = 4;
    $this->pagination->initialize($config);
    
    //obtengo lista de departamentos
    $tabla = array();
    foreach ($materias as $i => $materia) {
      $tabla[$i]=array(
        'IdMateria' => $materia->IdMateria,
        'Nombre' => $materia->Nombre,
        'Codigo' => $materia->Codigo,
        'Alumnos' => $materia->Alumnos
       );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_materias', $data);
  }
  
    
}

?>