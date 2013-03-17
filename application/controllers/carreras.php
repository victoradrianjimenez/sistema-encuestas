<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  var $data = array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    $this->data['resultadoTipo'] = ALERT_ERROR;
    $this->data['resultadoOperacion'] = null;
  }
  
  
  public function index(){
    $this->listar();
  }
  
  
  /*
   * Muestra el listado de carreras.
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
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo lista de carreras
    $carreras = $this->gc->listar($pagInicio, PER_PAGE);
    $lista = array();
    foreach ($carreras as $i => $carrera) {
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $director = $this->gu->dame($carrera->idDirectorCarrera);
      $lista[$i] = array(
        'carrera' => $carrera,
        'departamento' => ($departamento)?$departamento:$this->Departamento,
        'director' => ($director)?$director:$this->Usuario
      );      
    }
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("carreras/listar"),
      'total_rows' => $this->gc->cantidad(),
    ));
    
    //envio datos a la vista
    $this->Carrera->plan = date('Y');
    $this->data['lista'] = &$lista; //array de datos de las Carreras
    $this->data['carrera'] = &$this->Carrera; //datos por defecto de una nueva carrera
    $this->data['departamento'] = &$this->Departamento;
    $this->data['director'] = &$this->Usuario;
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->load->view('lista_carreras', $this->data);
  }


  /*
   * Ver y editar datos relacionados a una carrera
   */
  public function ver($idCarrera=null, $pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idCarrera = (int)$idCarrera;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');    
    $carrera = $this->gc->dame($idCarrera);
    if ($carrera){
      //obtengo lista de materias
      $lista = $carrera->listarMaterias($pagInicio, PER_PAGE);
      
      //genero la lista de links de paginación
      $this->pagination->initialize(array(
        'base_url' => site_url("carreras/ver/$idCarrera"),
        'total_rows' => $carrera->cantidadMaterias(),
        'uri_segment' => 4
      ));
      
      //envio datos a la vista
      $departamento = $this->gd->dame($carrera->idDepartamento);
      $director = $this->gu->dame($carrera->idDirectorCarrera);
      $this->data['carrera'] = $carrera;
      $this->data['departamento'] = ($departamento)?$departamento:$this->Departamento;
      $this->data['director'] = ($director)?$director:$this->Usuario;
      $this->data['lista'] = &$lista; //array de datos de las materias de la carrera
      $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
      $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
      $this->load->view('ver_carrera', $this->data);
    }
    else{
      show_error('El Identificador de Carrera no es válido.');
    }
  }


  /*
   * Recepción del formulario para agregar nueva carrera
   * POST: idDepartamento, idDirectorCarrera, nombre, plan, publicarInforme, publicarHistorico
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      $idDirectorCarrera = (int)$this->input->post('idDirectorCarrera');
      //agrego carrera y cargo vista para mostrar resultado
      $res = $this->gc->alta( (int)$this->input->post('idDepartamento'), 
                              ($idDirectorCarrera=='')?NULL:$idDirectorCarrera, 
                              $this->input->post('nombre',TRUE), 
                              (int)$this->input->post('plan'), 
                              (bool)$this->input->post('publicarInforme'), 
                              (bool)$this->input->post('publicarHistorico'));
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', "La operación se realizó con éxito. El ID de la nueva carrera es $res.");
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('carreras/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Carrera');
    $this->Carrera->plan = date('Y');
    $this->data['carrera'] = &$this->Carrera; //datos por defecto de una nueva carrera
    $this->data['departamento'] = &$this->Departamento;
    $this->data['director'] = &$this->Usuario;
    $this->data['tituloFormulario'] = 'Nueva Carrera';
    $this->data['urlFormulario'] = site_url('carreras/nueva');
    $this->load->view('editar_carrera', $this->data);
  }


  /*
   * Recepción del formulario para modificar los datos de una carrera
   * POST: idCarrera, idDirectorCarrera, idDepartamento, nombre, plan, publicarInforme, publicarHistorico
   */
  public function modificar($idCarrera=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //cargo modelos, librerias, etc.
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    
    //verifico datos POST
    $idCarrera = ($this->input->post('idCarrera')) ? (int)$this->input->post('idCarrera') : (int)$idCarrera;
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      $idDirectorCarrera = $this->input->post('idDirectorCarrera',TRUE);
      //modifico carrera y cargo vista para mostrar resultado
      $res = $this->gc->modificar($idCarrera, 
                                  (int)$this->input->post('idDepartamento'), 
                                  ($idDirectorCarrera=='')?NULL:$idDirectorCarrera, 
                                  $this->input->post('nombre',TRUE),
                                  (int)$this->input->post('plan'), 
                                  (bool)$this->input->post('publicarInforme'),
                                  (bool)$this->input->post('publicarHistorico'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La modificación de la carrera se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('carreras/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    if ($idCarrera==0) redirect('carreras/nueva');
    //obtengo datos de la carrera
    $this->load->model('Departamento');
    $this->load->model('Usuario');
    $this->load->model('Gestor_departamentos','gd');
    $this->load->model('Gestor_usuarios','gu');
    $carrera = $this->gc->dame((int)$idCarrera);
    $departamento = ($carrera)?$this->gd->dame($carrera->idDepartamento):$this->Departamento;
    $director = ($carrera)?$this->gu->dame($carrera->idDirectorCarrera):$this->Usuario;

    $this->data['carrera'] =($carrera)?$carrera:$this->Carrera;
    $this->data['departamento'] = ($departamento)?$departamento:$this->Departamento;
    $this->data['director'] = ($director)?$director:$this->Usuario;
    $this->data['tituloFormulario'] = 'Nueva Carrera';
    $this->data['urlFormulario'] = site_url('carreras/modificar/'.$idCarrera);
    $this->load->view('editar_carrera', $this->data);
  }


  /*
   * Recepción del formulario para eliminar una carrera
   * POST: idCarrera
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gc->baja((int)$this->input->post('idCarrera'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La carrera se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('carreras/listar');
  }


  /*
   * Recepción del formulario para crear una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function asociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $idCarrera = (int)$this->input->post('idCarrera');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->asociarMateria((int)$this->input->post('idMateria'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      } 
    }
    redirect('carreras/ver/'.$idCarrera);
  }


  /*
   * Recepción del formulario para eliminar una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function desasociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('carreras/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $idCarrera = (int)$this->input->post('idCarrera');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera;
      //elimino la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->desasociarMateria((int)$this->input->post('idMateria'));
      if (strcmp($res, 'ok')==0){
        $this->session->set_flashdata('resultadoOperacion', 'La operación se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }   
    }
    redirect('carreras/ver/'.$idCarrera);
  }


  /*
   * Método para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar');
      $this->load->model('Carrera');
      $this->load->model('Gestor_carreras','gc');
      $carreras = $this->gc->buscar($buscar);
      echo "\n";
      foreach ($carreras as $carrera) {
        echo  "$carrera->idCarrera\t".
              "$carrera->nombre\t".
              "$carrera->plan\t\n";
      }
    }
  }
  
  
  /*
   * Método para responder solicitudes AJAX
   * POST: idCarrera, buscar
   */
  public function buscarMateriasAJAX(){
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $idCarrera = $this->input->post('idCarrera',TRUE);
      $buscar = $this->input->post('buscar',TRUE);
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera; 
      $materias = $this->Carrera->buscarMaterias($buscar);
      echo "\n";
      foreach ($materias as $materia) {
        echo  "$materia->idMateria\t".
              "$materia->nombre\t".
              "$materia->codigo\t\n";
      }
    }
  }


  /*
   * Método para responder solicitudes AJAX
   * POST: idCarrera
   */
  public function listarMateriasAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $idCarrera = $this->input->post('idCarrera');
      $this->load->model('Materia');
      $this->load->model('Carrera');
      $this->Carrera->idCarrera = $idCarrera;
      $materias = $this->Carrera->listarMaterias(0,1000);
      echo "\n";
      foreach ($materias as $materia) {
        echo  "$materia->idMateria\t".
              "$materia->nombre\t".
              "$materia->codigo\t\n";
      }
    }
  }
}

?>