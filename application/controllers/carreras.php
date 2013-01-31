<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  var $data=array(); //datos para mandar a las vistas
  const per_page = 10; //cuantos items se mostraran por pagina en un listado
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //datos de session para enviarse a las vistas
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row(); 
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); 
    
  }
  
  public function index(){
    $this->listar();
  }
  
  /*
   * Muestra el listado de carreras.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos para continuar    
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');
    
    //obtengo lista de carreras
    $carreras = $this->gc->listar($pagInicio, self::per_page);
    $tabla = array();
    foreach ($carreras as $i => $carrera) {
      $tabla[$i] = array(
        'idCarrera' => $carrera->idCarrera,
        'idCarrera' => $carrera->idCarrera,
        'nombre' => $carrera->nombre,
        'plan' => $carrera->plan 
      );
      $departamento = $this->gd->dame($carrera->idDepartamento);
      if ($departamento){
        $tabla[$i]['departamento'] = array(
          'idDepartamento' => $departamento->idDepartamento,
          'nombre' => ($departamento)?$departamento->nombre:''
        );
      }
      else{
        $tabla[$i]['departamento'] = array(
          'idDepartamento' => null,
          'nombre' => ''
        );
      }
      if ($carrera->idDirectorCarrera){
        $director = $this->ion_auth->user($carrera->idDirectorCarrera)->row();
        $tabla[$i]['director'] = array(
          'nombre' => $director->nombre,
          'apellido' => $director->apellido
        );
      }
      else{
        $tabla[$i]['director'] = array(
          'nombre' => '',
          'apellido' => ''
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
    $this->data['tabla'] = &$tabla; //array de datos de las Carreras
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_carreras', $this->data);
  }

  /*
   * Ver y editar datos relacionados a una carrera
   */
  public function ver($idCarrera=null, $pagInicio=0){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;
    $idCarrera = (int)$idCarrera;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');    
    $carrera = $this->gc->dame($idCarrera);
    if ($carrera){
      $this->data['carrera'] = array(
        'idCarrera' => $carrera->idCarrera,
        'idDepartamento' => $carrera->idDepartamento,
        'idDirectorCarrera' => $carrera->idDirectorCarrera,
        'nombre' => $carrera->nombre,
        'plan' => $carrera->plan,
      );
      $departamento = $this->gd->dame($carrera->idDepartamento);
      if ($departamento){
        $this->data['carrera']['departamento'] = array(
          'idDepartamento' => $departamento->idDepartamento,
          'nombre' => ($departamento)?$departamento->nombre:''
        );
      }
      else{
        $this->data['carrera']['departamento'] = array(
          'idDepartamento' => null,
          'nombre' => ''
        );
      }
      if ($carrera->idDirectorCarrera){
        $director = $this->ion_auth->user($carrera->idDirectorCarrera)->row();
        $this->data['carrera']['director'] = array(
          'nombre' => $director->nombre,
          'apellido' => $director->apellido
        );
      }
      else{
        $this->data['carrera']['director'] = array(
          'nombre' => '',
          'apellido' => ''
        );
      }      
      //obtengo lista de materias
      $materias = $carrera->listarMaterias($pagInicio, self::per_page);
      $tabla = array();
      foreach ($materias as $i => $materia) {
        $tabla[$i]=array(
          'idMateria' => $materia->idMateria,
          'nombre' => $materia->nombre,
          'codigo' => $materia->codigo,
         );
      }
      //genero la lista de links de paginación
      $config['base_url'] = site_url("carreras/ver/$idCarrera");
      $config['total_rows'] = $carrera->cantidadMaterias();
      $config['per_page'] = self::per_page;
      $config['uri_segment'] = 4;
      $this->pagination->initialize($config);
      
      //envio datos a la vista
      $this->data['tabla'] = &$tabla; //array de datos de los Departamentos
      $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $this->load->view('ver_carrera', $this->data);
    }
    else{
      show_error('El Identificador de Carrera no es válido.');
    }
  }

  /*
   * Recepción del formulario para agregar nueva carrera
   * POST: idDepartamento, nombre, plan
   */
  public function nueva(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      $idDirectorCarrera = $this->input->post('idDirectorCarrera',TRUE);
      
      //agrego carrera y cargo vista para mostrar resultado
      $res = $this->gc->alta($this->input->post('idDepartamento',TRUE), ($idDirectorCarrera=='')?NULL:$idDirectorCarrera, $this->input->post('nombre',TRUE), $this->input->post('plan',TRUE));
      $this->data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $this->data['link'] = site_url("carreras/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para modificar los datos de una carrera
   * POST: idCarrera, idDepartamento, nombre, plan
   */
  public function modificar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idDirectorCarrera','Director de Carrera','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('plan','Plan','is_natural_no_zero|less_than[2100]|greater_than[1900]|required');      
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      $idCarrera = $this->input->post('idCarrera',TRUE);
      $idDirectorCarrera = $this->input->post('idDirectorCarrera',TRUE);
      
      //modifico carrera y cargo vista para mostrar resultado
      $res = $this->gc->modificar($idCarrera, $this->input->post('idDepartamento',TRUE), ($idDirectorCarrera=='')?NULL:$idDirectorCarrera, $this->input->post('nombre',TRUE),$this->input->post('plan',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("carreras/ver/$idCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idCarrera',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una carrera
   * POST: idCarrera
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_carreras','gc');
      
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gc->baja($this->input->post('idCarrera',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("carreras/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Recepción del formulario para crear una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function asociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $idCarrera = $this->input->post('idCarrera',TRUE);
      $this->Carrera->idCarrera = $idCarrera;
      
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->asociarMateria($this->input->post('idMateria', TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("carreras/ver/$idCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idCarrera',TRUE));
    }
  }

  /*
   * Recepción del formulario para eliminar una asociacion entre una materia y una carrera
   * POST: idMateria, idCarrera
   */
  public function desasociarMateria(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->in_group('admin')){
      show_error('No tiene permisos para ingresar a esta sección.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idMateria','Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('idCarrera','Carrera','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Carrera');
      $idCarrera = $this->input->post('idCarrera',TRUE);
      $this->Carrera->idCarrera = $idCarrera;
      
      //elimino la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->desasociarMateria($this->input->post('idMateria', TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("carreras/ver/$idCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($this->input->post('idCarrera',TRUE));
    }
  }

  /*
   * Método para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
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
  
  /*
   * Método para responder solicitudes AJAX
   * POST: idCarrera, buscar
   */
  public function buscarMateriasAJAX(){
    $idCarrera = $this->input->post('idCarrera');
    $buscar = $this->input->post('buscar');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->Carrera->IdCarrera = $idCarrera; 
    $materias = $this->Carrera->buscarMaterias($buscar);
    echo "\n";
    foreach ($materias as $materia) {
      echo  "$materia->idMateria\t".
            "$materia->nombre\t".
            "$materia->codigo\t\n";
    }
  }

  /*
   * Método para responder solicitudes AJAX
   */
  public function listarAJAX(){
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    $carreras = $this->gc->listar(0,1000);
    echo "\n";
    foreach ($carreras as $carrera) {
      echo  "$carrera->idCarrera\t".
            "$carrera->nombre\t".
            "$carrera->plan\t\n";
    }
  }
  /*
   * Método para responder solicitudes AJAX
   * POST: idCarrera
   */
  public function listarMateriasAJAX(){
    $idCarrera = $this->input->post('idCarrera');
    //VERIFICAR
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

?>