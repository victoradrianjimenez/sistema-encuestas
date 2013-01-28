<?php

/**
 * 
 */
class Materias extends CI_Controller{
  
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de materias.
   */
  public function listar($PagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    
    //obtengo lista de materias
    $materias = $this->gm->listar($PagInicio, self::per_page);
    $tabla = array();
    foreach ($materias as $i => $materia) {
      $tabla[$i]=array(
        'IdMateria' => $materia->IdMateria,
        'Nombre' => $materia->Nombre,
        'Codigo' => $materia->Codigo,
        'Alumnos' => $materia->Alumnos
       );
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("materias/listar");
    $config['total_rows'] = $this->gm->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);

    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('lista_materias', $data);
  }

  /*
   * Ver y editar datos relacionados a una materia
   */
  public function ver($IdMateria=null, $PagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    $IdMateria = (int)$IdMateria;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $materia = $this->gm->dame($IdMateria);
    if ($materia){
      $data['materia'] = array(
        'IdMateria' => $materia->IdMateria,
        'Nombre' => $materia->Nombre,
        'Codigo' => $materia->Codigo,
        'Alumnos' => $materia->Alumnos
      );
      //obtengo lista de datos de docentes
      $docentes = $materia->listarDocentes();
      $tabla = array();
      foreach ($docentes as $i => $docente) {
        $tabla[$i]=array(
          'IdPersona'=> $docente->IdPersona,
          'Nombre'=> $docente->Nombre,
          'Apellido'=> $docente->Apellido
         );
      }
      //genero la lista de links de paginación
      $config['base_url'] = site_url("materias/ver/$IdMateria");
      $config['total_rows'] = $materia->cantidadDocentes();
      $config['per_page'] = self::per_page;
      $config['uri_segment'] = 4;
      $this->pagination->initialize($config);
      
      $data['tabla'] = &$tabla;
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('ver_materia', $data);
    }
    else{
      show_error('El Identificador de Materia no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva materia
   * POST: Nombre, Codigo
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      
      //agrego materia y cargo vista para mostrar resultado
      $res = $this->gm->alta($this->input->post('Nombre',TRUE), $this->input->post('Codigo',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $data['link'] = site_url('materias'); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para eliminar una materia
   * POST: IdMateria
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gm->baja($this->input->post('IdMateria',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de una materia
   * POST: IdMateria, Nombre, Codigo
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdMateria', 'ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_materias','gm');
      $IdMateria = $this->input->post('IdMateria',TRUE);
      
      //modifico Materia y cargo vista para mostrar resultado
      $res = $this->gm->modificar($IdMateria, $this->input->post('Nombre',TRUE), $this->input->post('Codigo',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias/ver/$IdMateria"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdMateria',TRUE));
    }
  }

  /*
   * Recepción del formulario para crear una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria, TipoAcceso, OrdenFormulario, Cargo
   */
  public function asociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDocente','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdMateria','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('TipoAcceso','Tipo de acceso','alpha|exact_length[1]|required');
    $this->form_validation->set_rules('OrdenFormulario','Orden de formulario','is_natural|required');
    $this->form_validation->set_rules('Cargo','Cargo','alpha_dash_space|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->IdMateria = $this->input->post('IdMateria',TRUE);
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->asociarDocente(
                                $this->input->post('IdDocente', TRUE),
                                $this->input->post('TipoAcceso', TRUE), 
                                $this->input->post('OrdenFormulario', TRUE), 
                                $this->input->post('Cargo', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url('materias/ver/'.$this->Materia->IdMateria); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdMateria',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria
   */
  public function desasociarDocente(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDocente','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdMateria','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Materia');
      $this->Materia->IdMateria = $this->input->post('IdMateria',TRUE);
      
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->desasociarDocente($this->input->post('IdDocente', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias/ver/".$this->Materia->IdMateria); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdMateria',TRUE));
    }
  }
  
  //funcion para responder solicitudes AJAX
  public function buscarAJAX(){
    $buscar = $this->input->post('Buscar');
    //VERIFICAR
    $this->load->model('Materia');
    $this->load->model('Gestor_materias','gm');
    $materias = $this->gm->buscar($buscar);
    echo "\n";
    foreach ($materias as $materia) {
      echo  "$materia->IdMateria\t".
            "$materia->Nombre\t".
            "$materia->Codigo\t\n";
    }
  }
  
  //funcion para responder solicitudes AJAX
  public function cantidadAlumnosAJAX(){
    $IdMateria = $this->input->post('IdMateria');
    //VERIFICAR
    $this->load->model('Materia');
    $this->load->model('Gestor_materias', 'gm');
    $materia = $this->gm->dame($IdMateria);
    echo ($materia)?$materia->Alumnos:0;
  }
  
}

?>