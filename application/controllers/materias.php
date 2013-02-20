<?php

/**
 * 
 */
class Materias extends CI_Controller{
  
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
   * Muestra el listado de materias.
   * Última revisión: 2012-02-01 3:35 p.m.
   */
  public function listar($pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    
    //obtengo lista de materias
    $lista = $this->gm->listar($pagInicio, self::per_page);
    
    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("materias/listar"),
      'total_rows' => $this->gm->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));

    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de las materias
    $this->data['materia'] = &$this->Materia; //datos por defecto de una nueva materia
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_materias', $this->data);
  }

  /*
   * Ver y editar datos relacionados a una materia
   * Última revisión: 2012-02-01 3:39 p.m.
   */
  public function ver($idMateria=null, $pagInicio=0){
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idMateria = (int)$idMateria;

    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $materia = $this->gm->dame($idMateria);
    if ($materia){
      //obtengo lista de datos de docentes
      $lista = $materia->listarDocentes($pagInicio, self::per_page);

      //genero la lista de links de paginación
      $this->pagination->initialize(array(
        'base_url' => site_url("materias/ver/$idMateria"),
        'total_rows' => $materia->cantidadDocentes(),
        'per_page' => self::per_page,
        'uri_segment' => 4
      ));

      $this->data['lista'] = &$lista;
      $this->data['materia'] = &$materia;
      $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->load->view('ver_materia', $this->data);
    }
    else{
      show_error('El Identificador de Materia no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva materia
   * POST: nombre, codigo
   * Última revisión: 2012-02-01 3:44 p.m.
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_rules('alumnos','Alumnos','is_natural|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      
      //agrego materia y cargo vista para mostrar resultado
      $res = $this->gm->alta($this->input->post('nombre',TRUE), $this->input->post('codigo',TRUE), $this->input->post('alumnos',TRUE));
      $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $this->data['link'] = site_url('materias'); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para eliminar una materia
   * POST: idMateria
   * Última revisión: 2012-02-01 3:44 p.m.
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gm->baja($this->input->post('idMateria',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("materias/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de una materia
   * POST: idMateria, nombre, codigo
   * Última revisión: 2012-02-01 3:45 p.m.
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria', 'Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_rules('alumnos','Alumnos','is_natural|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      $idMateria = $this->input->post('idMateria',TRUE);
      
      //modifico Materia y cargo vista para mostrar resultado
      $res = $this->gm->modificar($idMateria, $this->input->post('nombre',TRUE), $this->input->post('codigo',TRUE), $this->input->post('alumnos',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("materias/ver/$idMateria"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idMateria',TRUE));
    }
  }

  /*
   * Recepción del formulario para crear una asociacion entre un docente y una materia
   * POST: idDocente, idMateria, ordenFormulario, cargo
   * Última revisión: 2012-02-01 3:45 p.m.
   */
  public function asociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDocente','Docente','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('ordenFormulario','Orden en formulario','is_natural_no_zero|required');
    $this->form_validation->set_rules('cargo','Cargo','alpha_dash_space|max_length[40]');
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->idMateria = $this->input->post('idMateria',TRUE);
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->asociarDocente($this->input->post('idDocente', TRUE), $this->input->post('ordenFormulario', TRUE), $this->input->post('cargo', TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url('materias/ver/'.$this->Materia->idMateria); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idMateria',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria
   * Última revisión: 2012-02-01 3:47 p.m.
   */
  public function desasociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDocente','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idMateria','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->idMateria = $this->input->post('idMateria',TRUE);
      
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->desasociarDocente($this->input->post('idDocente', TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("materias/ver/".$this->Materia->idMateria); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idMateria',TRUE));
    }
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   * Última revisión: 2012-02-01 3:48 p.m.
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar');
      $this->load->model('Materia');
      $this->load->model('Gestor_materias','gm');
      $materias = $this->gm->buscar($buscar);
      echo "\n";
      foreach ($materias as $materia) {
        echo  "$materia->idMateria\t".
              "$materia->nombre\t".
              "$materia->codigo\t\n";
      }
    }
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   * Última revisión: 2012-02-01 3:51 p.m.
   */
  public function listarCarrerasAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $this->load->model('Materia');
      $this->Materia->idMateria = $this->input->post('idMateria');
      $carreras = $this->Materia->listarCarreras();
      echo "\n";
      foreach ($carreras as $carrera) {
        echo  "$carrera->idCarrera\t".
              "$carrera->nombre\t".
              "$carrera->plan\t\n";
      }
    }
  }
}
?>