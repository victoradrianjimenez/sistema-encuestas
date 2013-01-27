<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de carreras.
   */
  public function listar($PagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo lista de carreras
    $carreras = $this->gc->listar($PagInicio, self::per_page);
    $tabla = array();
    foreach ($carreras as $i => $carrera) {
      $tabla[$i] = array(
        'IdCarrera' => $carrera->IdCarrera,
        'Nombre' => $carrera->Nombre,
        'Plan' => $carrera->Plan 
      );
      $departamento = $this->gd->dame($carrera->IdDepartamento);
      if ($departamento){
        $tabla[$i]['Departamento'] = array(
          'IdDepartamento' => $departamento->IdDepartamento,
          'Nombre' => ($departamento)?$departamento->Nombre:''
         );
      }
      else{
        $tabla[$i]['Departamento'] = array(
          'IdDepartamento' => null,
          'Nombre' => ''
         );
      }
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("carreras/listar");
    $config['total_rows'] = $this->gc->cantidad();
    $config['per_page'] = self::per_page;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //envio datos a la vista
    $data['tabla'] = &$tabla; //array de datos de las Carreras
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('lista_carreras', $data);
  }

  /*
   * Ver y editar datos relacionados a una carrera
   */
  public function ver($IdCarrera=null, $PagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    $IdCarrera = (int)$IdCarrera;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $carrera = $this->gc->dame($IdCarrera);
    if ($carrera){
      $data['carrera'] = array(
        'IdCarrera' => $carrera->IdCarrera,
        'IdDepartamento' => $carrera->IdDepartamento,
        'Nombre' => $carrera->Nombre,
        'Plan' => $carrera->Plan
      );
      //obtengo lista de materias
      $materias = $carrera->listarMaterias($PagInicio, self::per_page);
      $tabla = array();
      foreach ($materias as $i => $materia) {
        $tabla[$i]=array(
          'IdMateria' => $materia->IdMateria,
          'Nombre' => $materia->Nombre,
          'Codigo' => $materia->Codigo,
         );
      }
      //genero la lista de links de paginación
      $config['base_url'] = site_url("carreras/ver/$IdCarrera");
      $config['total_rows'] = $carrera->cantidadMaterias();
      $config['per_page'] = self::per_page;
      $config['uri_segment'] = 4;
      $this->pagination->initialize($config);
      
      //envio datos a la vista
      $data['tabla'] = &$tabla; //array de datos de los Departamentos
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('ver_carrera', $data);
    }
    else{
      show_error('El Identificador de Carrera no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva carrera
   * POST: IdDepartamento, Nombre, Plan
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      
      //agrego carrera y cargo vista para mostrar resultado
      $IdDepartamento = $this->input->post('IdDepartamento',TRUE);
      $res = $this->gc->alta(($IdDepartamento=='')?NULL:$IdDepartamento, $this->input->post('Nombre',TRUE), $this->input->post('Plan',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $data['link'] = site_url("carreras"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de una carrera
   * POST: IdCarrera, IdDepartamento, Nombre, Plan
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      $IdCarrera = $this->input->post('IdCarrera',TRUE);
      
      //modifico carrera y cargo vista para mostrar resultado
      $IdDepartamento = $this->input->post('IdDepartamento',TRUE);
      $res = $this->gc->modificar($IdCarrera, ($IdDepartamento=='')?NULL:$IdDepartamento, $this->input->post('Nombre',TRUE),$this->input->post('Plan',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/ver/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdCarrera',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una carrera
   * POST: IdCarrera
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gc->baja($this->input->post('IdCarrera',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para crear una asociacion entre una materia y una carrera
   * POST: IdMateria, IdCarrera
   */
  public function asociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $IdCarrera = $this->input->post('IdCarrera',TRUE);
      $this->Carrera->IdCarrera = $IdCarrera;
      
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->asociarMateria($this->input->post('IdMateria', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/ver/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdCarrera',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una asociacion entre una materia y una carrera
   * POST: IdMateria, IdCarrera
   */
  public function desasociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene persmisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $IdCarrera = $this->input->post('IdCarrera',TRUE);
      $this->Carrera->IdCarrera = $IdCarrera;
      
      //elimino la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->desasociarMateria($this->input->post('IdMateria', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/ver/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('IdCarrera',TRUE));
    }
  }

  /*
   * Método para responder solicitudes AJAX
   * POST: Buscar
   */
  public function buscarAJAX(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    $carreras = $this->gc->buscar($buscar);
    echo "\n";
    foreach ($carreras as $carrera) {
      echo  "$carrera->IdCarrera\t".
            "$carrera->Nombre\t".
            "$carrera->Plan\t\n";
    }
  }
  
  /*
   * Método para responder solicitudes AJAX
   * POST: IdCarrera, Buscar
   */
  public function buscarMateriasAJAX(){
    $idCarrera = $this->input->post('IdCarrera');
    $buscar = $this->input->post('Buscar');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->Carrera->IdCarrera = $idCarrera; 
    $materias = $this->Carrera->buscarMaterias($buscar);
    echo "\n";
    foreach ($materias as $materia) {
      echo  "$materia->IdMateria\t".
            "$materia->Nombre\t".
            "$materia->Codigo\t\n";
    }
  }

  /*
   * Método para responder solicitudes AJAX
   * POST: Buscar
   */
  public function listarAJAX(){
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    $carreras = $this->gc->listar(0,1000);
    echo "\n";
    foreach ($carreras as $carrera) {
      echo  "$carrera->IdCarrera\t".
            "$carrera->Nombre\t".
            "$carrera->Plan\t\n";
    }
  }
  //funcion para responder solicitudes AJAX
  public function listarMateriasAJAX(){
    $IdCarrera = $this->input->post('IdCarrera');
    //VERIFICAR
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->Carrera->IdCarrera = $IdCarrera;
    $materias = $this->Carrera->listarMaterias(0,1000);
    foreach ($materias as $materia) {
      echo  "$materia->IdMateria\t".
            "$materia->Nombre\t".
            "$materia->Codigo\t\n";
    }
  }
}

?>