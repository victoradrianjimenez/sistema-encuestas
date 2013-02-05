<?php

/**
 * 
 */
class Formularios extends CI_Controller{

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
   * Muestra el listado de formularios
   * Última revisión: 2012-02-04 1:48 p.m.
   */
  public function listar($PagInicio=0){
    if (!$this->ion_auth->logged_in()){redirect('/'); return;}
    //chequeo parámetros de entrada
    $PagInicio = (int)$PagInicio;
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    
    //obtengo lista de formularios
    $lista = $this->gf->listar($PagInicio, self::per_page);

    //genero la lista de links de paginación
    $this->pagination->initialize(array(
      'base_url' => site_url("formularios/listar"),
      'total_rows' => $this->gf->cantidad(),
      'per_page' => self::per_page,
      'uri_segment' => 3
    ));
    //envio datos a la vista
    $this->data['lista'] = &$lista; //array de datos de los formularios
    $this->data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $this->load->view('lista_formularios', $this->data);
  }

  /*
   * Muestra el formulario de edicion de formularios
   * Última revisión: 2012-02-04 3:27 p.m.
   */
  public function editar(){
    $this->load->view('editar_formulario', $this->data);
  }
  
  /*
   * Recepción del formulario para agregar nuevo formulario con sus secciones y preguntas
   * Última revisión: 2012-02-04 4:09 p.m.
   */
  public function nuevo(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('nombre','Nombre','alpha_dash_space|max_length[60]|required');
    $this->form_validation->set_rules('titulo','Título','alpha_dash_space|max_length[200]|required');
    $this->form_validation->set_rules('descripcion','Descripción','alpha_dash_space|max_length[200]');
    $this->form_validation->set_rules('preguntasAdicionales','Preguntas adicionales','is_natural_no_zero|required');      
    if($this->form_validation->run()){
      //cargo modelos y librerias necesarias
      $this->load->model('Seccion');
      $this->load->model('Formulario');
      $this->load->model('Gestor_formularios','gf');
      //leo los datos enviados por post, y los almaceno en un array
      $entradas = $this->input->post(NULL, TRUE);
      $datosSecciones = array();
      $res = null;
      $error = false;
      foreach ($entradas as $key => $x) {
        if (strpos($key, 'textoSeccion') !== false) {
          if (sscanf($key, "textoSeccion_%d",$i) == 1)
            $datosSecciones[$i]['texto'] = $x;
          else{
            $res="El texto de la sección no puede ser nulo.";
            $error=true;
            break;
          }
        }
        elseif (strpos($key, 'descripcionSeccion') !== false) {
          sscanf($key, "descripcionSeccion_%d", $i);
          $datosSecciones[$i]['descripcion'] = $x;
        }
        elseif (strpos($key, 'tipoSeccion') !== false) {
          if(sscanf($key, "tipoSeccion_%d", $i) == 1)
            $datosSecciones[$i]['tipo'] = $x;
          else{
            $res="El tipo de sección es incorrecto.";
            $error=true;
            break;
          }
        }
        elseif (strpos($key, 'idPregunta') !== false) {
          if(sscanf($key, "idPregunta_%d_%d", $i, $j) == 2)
            $datosSecciones[$i]['preguntas'][$j] = $x;
          else{
            $res="El identificador de la pregunta es incorrecto.";
            $error=true;
            break;
          }
        }
      }
      if(!$error){
        //doy de alta el formulario primero
        $res = $this->gf->alta($this->input->post('nombre', TRUE), $this->input->post('titulo', TRUE), $this->input->post('descripcion', TRUE), $this->input->post('preguntasAdicionales', TRUE));
        $error = !is_numeric($res);
      }
      if(!$error){
        $this->Formulario->idFormulario = (int)$res;
        foreach ($datosSecciones as $i => $seccion) {
          $res = $this->Formulario->altaSeccion(NULL, $seccion['texto'], $seccion['descripcion'], $seccion['tipo']);
          if (!is_numeric($res)){
            $error = true;
            break;
          }
          if(!isset($seccion['preguntas'])){
            $res = "Las secciones no pueden estar vacías.";
            $error = true;
            break;
          }
          $this->Seccion->idSeccion = (int)$res;
          $this->Seccion->idFormulario = $this->Formulario->idFormulario;
          foreach ($seccion['preguntas'] as $j => $pregunta) {
            $res = $this->Seccion->altaItem($pregunta, NULL, $j);
            echo $pregunta.' '.NULL.' '.$j;
            if ($res != 'ok'){
              $error = true;
              break;
            }
          }
          if ($error) break;
        }
      }
      if($error){
        
        $this->gf->baja($this->Formulario->idFormulario);
      }
      //cargo vista para mostrar resultado
      $this->data['mensaje'] = (!$error)?"La operación se realizó con éxito. El ID del nuevo formulario es ".$this->Formulario->idFormulario.".":$res;
      $this->data['link'] = site_url("formularios/listar"); //hacia donde redirigirse
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      echo "d'oh!";
      $this->editar();
    }
  }
  
  /*
   * Recepción del formulario para eliminar un formulario
   * POST: idFormulario
   * Última revisión: 2012-02-04 3:31 p.m.
   */
  public function eliminar(){
    //verifico si el usuario tiene permisos para continuar
    if (!$this->ion_auth->is_admin()){
      show_error('No tiene permisos para realizar esta operación.');
      return;
    }
    //verifico datos POST
    $this->form_validation->set_rules('idFormulario','Formulario','is_natural_no_zero|required');
    if($this->form_validation->run()){
      $this->load->model('Gestor_formularios','gf');

      //doy de baja y cargo vista para mostrar resultado
      $res = $this->gf->baja($this->input->post('idFormulario',TRUE));
      $this->data['mensaje'] = (strcmp($res, 'ok')==0)?'La operación se realizó con éxito.':$res;
      $this->data['link'] = site_url("formularios/listar"); //link para boton aceptar/continuar
      $this->load->view('resultado_operacion', $this->data);
    }
    else{
      //en caso de que los datos sean incorrectos, vuelvo a la pagina principal
      $this->listar();
    }
  }

  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   * Última revisión: 2012-02-05 3:42 p.m.
   */
  public function buscarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $buscar = $this->input->post('buscar');
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->buscar($buscar);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->idFormulario\t".
            "$formulario->nombre\t".
            "$formulario->creacion\t\n";
    }
  }
  
  /*
   * Funcion para responder solicitudes AJAX
   * POST: buscar
   * Última revisión: 2012-02-05 3:44 p.m.
   */
  public function listarAJAX(){
    if (!$this->ion_auth->logged_in()){return;}
    $this->load->model('Formulario');
    $this->load->model('Gestor_formularios','gf');
    $formularios = $this->gf->listar(0,1000);
    echo "\n";
    foreach ($formularios as $formulario) {
      echo  "$formulario->idFormulario\t".
            "$formulario->nombre\t".
            "$formulario->creacion\t\n";
    }
  }
}

?>