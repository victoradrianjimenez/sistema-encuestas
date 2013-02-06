<?php

/**
 * 
 */
class Devoluciones extends CI_Controller {
    
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado

  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de devoluciones.
   * Última revisión: 2012-02-06 4:41 p.m.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Gestor_materias', 'gm');
    $this->load->model('Gestor_devoluciones','gd');

    //obtengo lista de departamentos
    $devoluciones = $this->gd->listar($pagInicio, self::per_page);
    $lista = array(); //datos para mandar a la vista
    foreach ($devoluciones as $i => $devolucion) {
      $materia = $this->gm->dame($devolucion->idMateria);
      $lista[$i] = array(
        'devolucion' => $devolucion,
        'materia' => ($materia)?$materia:$this->Materia
      );
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("devoluciones/listar"),
      'total_rows' => $this->gd->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));
    
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las devoluciones
    $this->data['devolucion'] = &$this->Devolucion; //datos por defecto de una nueva devolucion
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_devoluciones', $this->data);
  }

  /*
   * Muestra el formulario de edicion de formularios
   * Última revisión: 2012-02-05 7:36 p.m.
   */
  public function editar(){
    $this->load->view('editar_devolucion', $this->data);
  }
  
  
}
