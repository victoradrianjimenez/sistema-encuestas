<?php

/**
 * Controlador para la gestión de departamentos
 */
class Departamentos extends CI_Controller{
  
  var $data = array(); //datos para mandar a las vistas
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
    //doy formato al mensaje de error de validación de formulario
    $this->form_validation->set_error_delimiters(ERROR_DELIMITER_START, ERROR_DELIMITER_END);
    //leo los datos del usuario logueado
    $this->data['usuarioLogin'] = $this->ion_auth->user()->row();
    //leo los mensajes generados en la página anterior
    $this->data['resultadoTipo'] = $this->session->flashdata('resultadoTipo');
    $this->data['resultadoOperacion'] = $this->session->flashdata('resultadoOperacion');
  }
  
  public function index(){
    //por defecto, muestro el listado de departamentos
    $this->listar();
  }

  /*
   * Muestra el listado de departamentos.
   */
  public function listar($pagInicio=0){
    //verifico si el usuario tiene permisos
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->in_group(array('admin','decanos','docentes'))){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('/');
    }

    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');

    //chequeo parámetros de entrada
    $pagInicio = (int)$pagInicio;

    //obtengo lista de departamentos
    $departamentos = $this->gd->listar($pagInicio, PER_PAGE);
    $lista = array(); //datos para mandar a la vista
    foreach ($departamentos as $i => $departamento) {
      $jefe = $this->gu->dame($departamento->idJefeDepartamento);
      $lista[$i] = array(
        'departamento' => $departamento,
        'jefeDepartamento' => ($jefe)?$jefe:$this->Usuario //si no tiene jefe, poner datos vacios de usuario
      );
    }

    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url('departamentos/listar'),
      'total_rows' => $this->gd->cantidad()
    ));

    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los Departamentos
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_departamentos', $this->data);
  }
  
  /*
   * Permite crear un nuevo departamento
   * POST: nombre, idJefeDepartamento, publicarInforme, publicarHistorico
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('departamentos/listar');
    }
    
    //cargo modelos y librerias necesarias
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Gestor_departamentos','gd');
    $this->load->model('Gestor_usuarios','gu');
    
    //leo los datos POST
    $this->Departamento->idDepartamento = null;
    $this->Departamento->idJefeDepartamento = ($this->input->post('idJefeDepartamento')>0) ? $this->input->post('idJefeDepartamento') : NULL; 
    $this->Departamento->nombre = $this->input->post('nombre',TRUE);
    $this->Departamento->publicarInformes = ($this->input->post('publicarInformes')) ? RESPUESTA_SI : RESPUESTA_NO;
    $this->Departamento->publicarHistoricos = ($this->input->post('publicarHistoricos')) ? RESPUESTA_SI : RESPUESTA_NO;
    
    //verifico datos POST
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');  
    if($this->form_validation->run()){
      //agrego departamento y muestro mensaje resultado
      $res = $this->gd->alta( $this->Departamento->idJefeDepartamento, 
                              $this->Departamento->nombre,
                              $this->Departamento->publicarInformes,
                              $this->Departamento->publicarHistoricos);
      //si la operación se realizó con éxito
      if (is_numeric($res)){
        $this->session->set_flashdata('resultadoOperacion', 'El nuevo departamento se agregó con éxito');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('departamentos/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if ($this->Departamento->idJefeDepartamento) $this->Usuario = $this->gu->dame($this->Departamento->idJefeDepartamento);
    $this->data['departamento'] = &$this->Departamento;
    $this->data['jefeDepartamento'] = &$this->Usuario;
    $this->data['tituloFormulario'] = 'Nuevo Departamento';
    $this->data['urlFormulario'] = site_url('departamentos/nuevo');
    $this->load->view('editar_departamento', $this->data);
  }

  /*
   * Modificar los datos de un departamento
   * POST: idDepartamento, idJefeDepartamento, nombre, publicarInforme, publicarHistorico
   */
  public function modificar($idDepartamento=null){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('departamentos/listar');
    }
    
    //cargo modelos, librerias, etc.
    $this->load->model('Usuario');
    $this->load->model('Departamento');
    $this->load->model('Gestor_usuarios','gu');
    $this->load->model('Gestor_departamentos','gd');
    
    //leo los datos POST
    $this->Departamento->idDepartamento = (int)$this->input->post('idDepartamento');
    $this->Departamento->idJefeDepartamento = ($this->input->post('idJefeDepartamento')>0) ? $this->input->post('idJefeDepartamento') : NULL;
    $this->Departamento->nombre = $this->input->post('nombre',TRUE);
    $this->Departamento->publicarInformes = ($this->input->post('publicarInformes')) ? RESPUESTA_SI : RESPUESTA_NO;
    $this->Departamento->publicarHistoricos = ($this->input->post('publicarHistoricos')) ? RESPUESTA_SI : RESPUESTA_NO;
    
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    $this->form_validation->set_rules('idJefeDepartamento','Jefe de Departamento','is_natural_no_zero');
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    if($this->form_validation->run()){
      //modifico departamento y cargo vista para mostrar resultado
      $res = $this->gd->modificar(  $this->Departamento->idDepartamento, 
                                    $this->Departamento->idJefeDepartamento, 
                                    $this->Departamento->nombre,
                                    $this->Departamento->publicarInformes,
                                    $this->Departamento->publicarHistoricos);
      //si la operación se realizó con éxito
      if (strcmp($res, PROCEDURE_SUCCESS)==0){
        $this->session->set_flashdata('resultadoOperacion', 'La modificación del departamento se realizó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
        redirect('departamentos/listar');
      }
      $this->data['resultadoOperacion'] = $res;
      $this->data['resultadoTipo'] = ALERT_ERROR;
    }
    //en caso de que los datos sean incorrectos, vuelvo a la pagina de edicion
    if (!$idDepartamento) redirect('departamentos/nuevo');
    $this->Departamento = $this->gd->dame((int)$idDepartamento);
    if (!$this->Departamento){
      $this->session->set_flashdata('resultadoOperacion', 'No existe el departamento seleccionado.');
      $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      redirect('departamentos/listar');
    }
    if ($this->Departamento->idJefeDepartamento) $this->Usuario = $this->gu->dame($this->Departamento->idJefeDepartamento);
    $this->data['departamento'] = &$this->Departamento;
    $this->data['jefeDepartamento'] = &$this->Usuario;
    $this->data['tituloFormulario'] = 'Modificar Departamento';
    $this->data['urlFormulario'] = site_url('departamentos/modificar/'.$idDepartamento);
    $this->load->view('editar_departamento', $this->data);
  }

  /*
   * Recepción del formulario para eliminar un departamento
   * POST: idDepartamento
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->logged_in()){
      $this->session->set_flashdata('resultadoOperacion', 'Debe iniciar sesión para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('usuarios/login');
    }
    elseif (!$this->ion_auth->is_admin()){
      $this->session->set_flashdata('resultadoOperacion', 'No tiene permisos para realizar esta operación.');
      $this->session->set_flashdata('resultadoTipo', ALERT_WARNING);
      redirect('departamentos/listar');
    }
    //verifico datos POST
    $this->form_validation->set_rules('idDepartamento','Departamento','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_departamentos','gd');
      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gd->baja((int)$this->input->post('idDepartamento'));
      if ($res == PROCEDURE_SUCCESS){
        $this->session->set_flashdata('resultadoOperacion', 'El departamento se eliminó con éxito.');
        $this->session->set_flashdata('resultadoTipo', ALERT_SUCCESS);
      }
      else{
        $this->session->set_flashdata('resultadoOperacion', $res);
        $this->session->set_flashdata('resultadoTipo', ALERT_ERROR);
      }
    }
    redirect('departamentos/listar');
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   */
  public function buscarAJAX(){
    $this->form_validation->set_rules('buscar','Buscar','required');
    if($this->form_validation->run()){
      $buscar = $this->input->post('buscar', TRUE);
      $this->load->model('Departamento');
      $this->load->model('Gestor_departamentos','gd');
      echo "\n";
      $departamentos = $this->gd->buscar($buscar);
      foreach ($departamentos as $departamento) {
        echo  "$departamento->idDepartamento\t".
              "$departamento->nombre\t\n";
      }
    }
  }
}

?>