<?php

/**
 * 
 */
class Devoluciones extends CI_Controller {
    
  var $data=array(); //datos para mandar a las vistas

  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de devoluciones, para una materia.
   * Última revisión: 2012-02-06 4:41 p.m.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    if($this->form_validation->run()){
      $idMateria = (int)$this->input->post('idMateria');
      
      //chequeo parámetros de entrada
      $pagInicio = (int)$pagInicio;
      
      //cargo modelos, librerias, etc.
      $this->load->library('pagination');
      $this->load->model('Materia');
      $this->load->model('Encuesta');
      $this->load->model('Devolucion');
      $this->load->model('Gestor_materias','gm');
      $this->load->model('Gestor_encuestas','ge');
      $this->load->model('Gestor_devoluciones','gd');

      $materia = $this->gm->dame($idMateria);
    
      //obtengo lista de departamentos
      $devoluciones = $this->gd->listar($idMateria, $pagInicio, PER_PAGE);
      $lista = array(); //datos para mandar a la vista
      foreach ($devoluciones as $i => $devolucion) {
        $encuesta = $this->ge->dame($devolucion->idEncuesta, $devolucion->idFormulario);
        $lista[$i] = array(
          'devolucion' => $devolucion,
          'encuesta' => ($encuesta)?$encuesta:$this->Encuesta
        );
      }
      //genero la lista de links de paginación
      $this->pagination->initialize(array(
        'base_url' => site_url("devoluciones/listar"),
        'total_rows' => $this->gd->cantidad($idMateria)
      ));
      
      //envio datos a la vista
      $this->data['lista'] = &$lista; //array de datos de las devoluciones
      $this->data['materia'] = &$materia;
      $this->data['devolucion'] = &$this->Devolucion; //datos por defecto de una nueva devolucion
      $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->load->view('lista_devoluciones', $this->data);
    }
    else{
      $this->load->view('solicitud_devoluciones', $this->data);
    }
  }

  /*
   * Muestra el formulario de edicion de formularios
   * POST: idMateria
   * Última revisión: 2012-02-05 7:36 p.m.
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','required|is_natural_no_zero');
    if($this->form_validation->run()){
      echo '2';
      //si se enviaron datos de la nueva devolucion
      if($this->input->post('idEncuesta')){
        //verifico otros datos POST
        $this->form_validation->set_rules('idEncuesta','Encuesta','required|is_natural_no_zero');
        $this->form_validation->set_rules('idFormulario','Formulario','required|is_natural_no_zero');
        $this->form_validation->set_rules('fortalezas','Fortalezas','alpha_dash_space');
        $this->form_validation->set_rules('debilidades','Debilidades','alpha_dash_space');
        $this->form_validation->set_rules('alumnos','Alumnos','alpha_dash_space');
        $this->form_validation->set_rules('docentes','Docentes','alpha_dash_space');
        $this->form_validation->set_rules('mejoras','Mejoras','alpha_dash_space');
        if($this->form_validation->run()){
          $this->load->model('Gestor_devoluciones','gd');
              
          //agrego devolucion y cargo vista para mostrar resultado
          $fortalezas = $this->input->post('fortalezas');
          $debilidades = $this->input->post('debilidades');
          $alumnos = $this->input->post('alumnos');
          $docentes = $this->input->post('docentes');
          $mejoras = $this->input->post('mejoras');
          $res = $this->gd->alta($this->input->post('idMateria'), $this->input->post('idEncuesta'), $this->input->post('idFormulario'),
                 ($fortalezas=='')?NULL:$fortalezas, ($debilidades=='')?NULL:$debilidades, ($alumnos=='')?NULL:$alumnos, ($docentes=='')?NULL:$docentes, ($mejoras=='')?NULL:$mejoras); 
          $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva devolución es $res.":$res;
          $this->data['link'] = site_url("devoluciones/listar"); //hacia donde redirigirse
          $this->load->view('resultado_operacion', $this->data);
          return;
        }
      }
      $this->load->model('Materia');
      $this->load->model('Gestor_materias','gm');
      $materia = $this->gm->dame($this->input->post('idMateria'));
      $this->data['materia'] = &$materia; 
      $this->load->view('editar_devolucion', $this->data);
    }
    else{
      $this->listar();
    }
  }

  /*
   * Ver una devolucion
   * Última revisión: 2012-02-20 12:10 p.m.
   */
  public function ver($idDevolucion, $idMateria, $idEncuesta, $idFormulario){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','jefes_departamentos','directores','docentes'))){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Materia');
    $this->load->model('Encuesta');
    $this->load->model('Devolucion');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_encuestas','ge');
    $this->load->model('Gestor_devoluciones','gd');
    
    //obtengo datos de la devolucion
    $encuesta = $this->ge->dame((int)$idEncuesta, (int)$idFormulario);
    $materia = $this->gm->dame((int)$idMateria);
    $devolucion = $this->gd->dame((int)$idDevolucion, (int)$idMateria, (int)$idEncuesta, (int)$idFormulario);
    if ($devolucion && $encuesta && $materia){
      //envio datos a la vista
      $this->data['devolucion'] = &$devolucion;
      $this->data['materia'] = &$materia;
      $this->data['encuesta'] = &$encuesta;
      $this->load->view('mostrar_devolucion', $this->data);
    }
    else{
      show_error('El Identificador de Devolución no es válido.');
    }
  }
  
  public function eliminar(){} //IMPLEMENTAR

}
