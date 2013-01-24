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
          'IdCarrera' => $carrera->IdCarrera,
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
    $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
    $this->load->view('lista_materias', $data);
  }
  
  
  /*
   * Ver todo lo relacionado a una materia
   */
  public function ver($idMateria=0, $pagInicio=0){
    if (!is_numeric($idMateria) || $idMateria<1){
      show_error('El Identificador de Materia no es válido.');
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
    $materia = $this->gm->dame($idMateria);
    if ($materia != FALSE){
      $data['materia'] = array(
        'IdMateria' => $materia->IdMateria,
        'Nombre' => $materia->Nombre,
        'Codigo' => $materia->Codigo,
        'Alumnos' => $materia->Alumnos
      );
      //obtengo lista de datos de docentes
      $cantidadDocentes = $materia->cantidadDocentes();
      $datosDocentes = $materia->listarDocentes($pagInicio, 5);
      //genero la lista de links de paginación
      $config['base_url'] = site_url("materias/ver/$idMateria");
      $config['total_rows'] = $cantidadDocentes;
      $config['per_page'] = 5;
      $config['uri_segment'] = 4;
      $this->pagination->initialize($config);
      //envio datos a la vista
      $data['tabla'] = $datosDocentes; //array de datos de los Departamentos
      $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de sesion
      $this->load->view('ver_materia', $data);
    }
    else{
      show_error('El Identificador de Materia no es válido.');
    }
  }


  /*
   * Agregar nueva materia
   * POST: Nombre, Codigo
   */
  public function nueva(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_materias','gm');
      //agrego materia y cargo vista para mostrar resultado
      $res = $this->gm->alta($this->input->post('Nombre',TRUE), $this->input->post('Codigo',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (is_numeric($res))?"La operación se realizó con éxito. El ID de la nueva carrera es $res.":$res;
      $data['link'] = site_url("materias/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->listar();
    }
  }

  /*
   * Eliminar una materia
   * POST: IdMateria
   */
  public function eliminar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdMateria = $this->input->post('IdMateria',TRUE);
    $this->form_validation->set_rules('IdMateria','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Gestor_materias','gm');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gm->baja($IdMateria);
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
   * Modificar los datos de una materia
   * POST: IdMateria, Nombre, Codigo
   */
  public function modificar(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdMateria = $this->input->post('IdMateria',TRUE);
    $this->form_validation->set_rules('IdMateria', 'ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('Nombre','Nombre','alpha_dash_space|required');
    $this->form_validation->set_rules('Codigo','Código','alpha_numeric|required|max_length[5]');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error      
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Materia');
      $this->load->model('Gestor_materias','gm');
      //modifico Materia y cargo vista para mostrar resultado
      $res = $this->gm->modificar($IdMateria, $this->input->post('Nombre',TRUE), $this->input->post('Codigo',TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias/ver/$IdMateria"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($IdMateria);
    }
  }


  /*
   * Crear una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria, TipoAcceso, OrdenFormulario, Cargo
   */
  public function asociarDocente(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdMateria = $this->input->post('IdMateria',TRUE);
    $this->form_validation->set_rules('IdDocente','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdMateria','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_rules('TipoAcceso','Tipo de acceso','alpha|exact_length[1]|required');
    $this->form_validation->set_rules('OrdenFormulario','Orden de formulario','is_natural|required');
    $this->form_validation->set_rules('Cargo','Cargo','alpha_dash_space|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Materia');
      $this->Materia->IdMateria = $IdMateria;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->asociarDocente(
                                $this->input->post('IdDocente', TRUE),
                                $this->input->post('TipoAcceso', TRUE), 
                                $this->input->post('OrdenFormulario', TRUE), 
                                $this->input->post('Cargo', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias/ver/$IdMateria"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($IdMateria);
    }
  }

  /*
   * Elimina una asociacion entre un docente y una materia
   * POST: IdDocente, IdMateria
   */
  public function desasociarDocente(){
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    $IdMateria = $this->input->post('IdMateria',TRUE);
    $this->form_validation->set_rules('IdDocente','ID Materia','is_natural_no_zero|required');
    $this->form_validation->set_rules('IdMateria','ID Carrera','is_natural_no_zero|required');
    $this->form_validation->set_error_delimiters('<small class="error">', '</small>'); //doy formato al mensaje de error
    if($this->form_validation->run()!=FALSE){
      $this->load->model('Materia');
      $this->Materia->IdMateria = $IdMateria;
      //creo la asociacion y cargo vista para mostrar resultado
      $res = $this->Materia->desasociarDocente($this->input->post('IdDocente', TRUE));
      $data['usuarioLogin'] = $this->ion_auth->user()->row(); //datos de session
      $data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $data['link'] = site_url("materias/ver/$IdMateria"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $data);      
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
      $this->ver($IdMateria);
    }
  }
  
  
  //funcion para responder solicitudes AJAX
  public function buscar(){
    $buscar = $this->input->post('Buscar');
    //VERIFICAR
    $this->load->model('Materia');
    $this->load->model('Gestor_materias','gm');
    $materias = $this->gm->buscar($buscar);
    foreach ($materias as $materia) {
      echo  "$materia->IdMateria\t".
            "$materia->Nombre\t".
            "$materia->Codigo\t\n";
    }
  }
  
}

?>