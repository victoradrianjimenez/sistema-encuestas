<?php

/**
 * 
 */
class Carreras extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  private function _datosDepartamentos(){
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $departamentos = $this->gd->listar(0, 255);
    $datos_departamentos = array();
    foreach ($departamentos as $i => $departamento) {
      $datos_departamentos[$i] = array(
        'IdDepartamento' => $departamento->IdDepartamento,
        'Nombre' => $departamento->Nombre);
    }
    return $datos_departamentos;
  }
  
  
  public function index(){
    $this->listar();
  }
  
  public function listar($idDepartamento=0, $pagInicio=0){
    if (!is_numeric($idDepartamento)){
      show_error('El Identificador de Departamento no es válido.');
      return;
    }
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Carrera');
    $this->load->model('Departamento');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_departamentos','gd');
    if ($idDepartamento == 0){
      $cantidadCarreras = $this->gc->cantidad();
      $carreras = $this->gc->listar($pagInicio, 5);
    }
    else{
      $departamento = $this->gd->dame($idDepartamento);
      if ($departamento != FALSE){
        $cantidadCarreras = $departamento->cantidadCarreras();
        $carreras = $departamento->listarCarreras($pagInicio, 5);
        $data['departamento'] = array('Nombre' => $departamento->Nombre);
      }
      else{
        show_error('El Identificador de Departamento no es válido.');
        return;
      }
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("carreras/listar/$idDepartamento");
    $config['total_rows'] = $cantidadCarreras;
    $config['per_page'] = 5;
    $config['uri_segment'] = 4;
    $this->pagination->initialize($config);
    
    //obtengo lista de carreras
    $tabla = array();
    foreach ($carreras as $i => $carrera) {
      $departamento = $this->gd->dame($carrera->IdDepartamento);
      $tabla[$i] = array(
        'IdCarrera' => $carrera->IdCarrera,
        'Nombre' => $carrera->Nombre,
        'Plan' => $carrera->Plan,
        'Departamento' => ($departamento!=FALSE)?$departamento->Nombre:''  
      );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de las Carreras
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_carreras', $data);
  }

  
  /*
   * Editar todo lo relacionado a una carrera
   * POST: IdCarrera, IdDepartamento, Nombre, Plan
   */
  public function editar($idCarrera=0, $pagInicio=0){
    if (!is_numeric($idCarrera) || $idCarrera<1){
      show_error('El Identificador de Carrera no es válido.');
      return;
    }
    elseif (!is_numeric($pagInicio)){
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
    $carrera = $this->gc->dame($idCarrera);
    if ($carrera != FALSE){
      $cantidadMaterias = $carrera->cantidadMaterias();
      $materias = $carrera->listarMaterias($pagInicio, 5);
      $data['carrera'] = array(
        'IdCarrera' => $carrera->IdCarrera,
        'IdDepartamento' => $carrera->IdDepartamento,
        'Nombre' => $carrera->Nombre,
        'Plan' => $carrera->Plan
      );
    }
    //genero la lista de links de paginación
    $config['base_url'] = site_url("carreras/editar/$idCarrera");
    $config['total_rows'] = $cantidadMaterias;
    $config['per_page'] = 5;
    $config['uri_segment'] = 4;
    $this->pagination->initialize($config);
    //obtengo lista de materias
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
    $this->load->view('editar_carrera', $data);
  }

  /*
   * Agregar nueva carrera
   * POST: IdDepartamento, Nombre, Plan
   */
  public function nueva(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('Nombre','Nombre','required');
    $this->form_validation->set_rules('Plan','Plan','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_carreras','gc');
      //agrego carrera y cargo vista para mostrar resultado
      $res = $this->gc->alta($this->input->post('IdDepartamento',TRUE), $this->input->post('Nombre',TRUE), $this->input->post('Plan',TRUE));
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $data['link'] = site_url("carreras/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Modificar los datos de una carrera
   * POST: IdCarrera, IdDepartamento, Nombre, Plan
   */
  public function modificar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdCarrera = $this->input->post('IdCarrera',TRUE);
    $this->form_validation->set_rules('IdDepartamento','ID Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('Nombre','Nombre','required');
    $this->form_validation->set_rules('Plan','Plan','required|is_natural_no_zero|less_than[2100]|greater_than[1900]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Carrera');
      $this->load->model('Gestor_carreras','gc');
      //modifico carrera y cargo vista para mostrar resultado
      $res = $this->gc->modificar($IdCarrera, $this->input->post('IdDepartamento',TRUE), $this->input->post('Nombre',TRUE),$this->input->post('Plan',TRUE));
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/editar/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->editar($IdCarrera);
    }
  }

  /*
   * Eliminar una carrera
   * POST: IdCarrera
   */
  public function eliminar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdCarrera = $this->input->post('IdCarrera',TRUE);
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_carreras','gc');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gc->baja($IdCarrera);
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
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
   * Crear una asociacion entre una materia y una carrera
   * POST: IdMateria, IdCarrera
   */
  public function asociarMateria(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdCarrera = $this->input->post('IdCarrera',TRUE);
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Carrera');
      $this->Carrera->IdCarrera = $IdCarrera;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->asociarMateria($this->input->post('IdMateria'), TRUE);
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/editar/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->editar($IdCarrera);
    }
  }

  /*
   * Elimina una asociacion entre una materia y una carrera
   * POST: IdMateria, IdCarrera
   */
  public function desasociarMateria(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdCarrera = $this->input->post('IdCarrera',TRUE);
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdCarrera','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Carrera');
      $this->Carrera->IdCarrera = $this->input->post('IdCarrera', TRUE);
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Carrera->desasociarMateria($this->input->post('IdMateria'), TRUE);
      $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("carreras/editar/$IdCarrera"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->editar($IdCarrera);
    }
  }

  /*
   * Método para responder solicitudes AJAX
   * POST: Buscar
   */
  public function buscar(){
    $buscar = $this->input->post('Buscar');
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras','gc');
    $carreras = $this->gc->buscar($buscar);
    foreach ($carreras as $carrera) {
      echo  "$carrera->IdCarrera\t".
            "$carrera->Nombre\t".
            "$carrera->Plan\t".
            "\n";
    }
  }
  
}

?>