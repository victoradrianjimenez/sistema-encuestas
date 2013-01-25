<?php

/**
 * 
 */
class Claves extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function encuesta(){
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Formulario');
    $this->load->model('Materia');
    $this->load->model('Gestor_materias', 'gm');
    $this->load->model('Carrera');
    $this->load->model('Gestor_carreras', 'gc');
    
    
    //SIMULO UN FORMULARIO
    $this->Formulario->IdFormulario = 1;
    $this->Formulario->Nombre = 'Encuesta Alumnos';
    $this->Formulario->Titulo = 'Encuesta para mejorar la calidad de la enseñanza';
    $this->Formulario->Descripcion = '';
    $this->Formulario->Creacion = '2013-01-01 00:00:00';
    $this->Formulario->PreguntasAdicionales = 10;
    $formulario = $this->Formulario;
    
    $carrera = $this->gc->dameCarrera(1);
    $materia = $this->gm->dameMateria(1);  
    $secciones = $formulario->listarSeccionesCarrera(1);
    
    $datos_secciones = array();
    foreach ($secciones as $i => $seccion) {
      $datos_items = array();
      $items = $seccion->listarItems();
      foreach ($items as $j => $item) {
        $datos_opciones = array();
        $opciones = $item->listarOpciones();
        foreach ($opciones as $k => $opcion) {
          $datos_opciones[$k]=array(
            'idOpcion' => $opcion->IdOpcion,
            'texto' => $opcion->Texto);
        }
        $datos_items[$j] = array(
          'idPregunta' => $item->IdPregunta,
          'texto' => $item->Texto,
          'descripcion' => $item->Descripcion,
          'tipo' => $item->Tipo,
          'obligatoria' => $item->Obligatoria,
          'limiteInferior' => $item->LimiteInferior,
          'limiteSuperior' => $item->LimiteSuperior,
          'paso' => $item->Paso,
          'unidad' => $item->Unidad,
          'tamaño' => $item->Tamaño,
          'opciones' => $datos_opciones);
      }
      $datos_secciones[$i] = array(
        'items' => $datos_items,
        'texto' => $seccion->Texto,
        'descripcion' => $seccion->Descripcion,
        'tipo' => $seccion->Tipo);
    }

    $datos_materia = array(
      'nombre' => $materia->Nombre,
      'codigo' => $materia->Codigo);
    $datos_carrera = array(
      'nombre' => $carrera->Nombre);
    $datos_formulario = array(
      'titulo' => $formulario->Titulo,
      'descripcion' => $formulario->Descripcion);
      
    $data = array(
      'formulario' => $datos_formulario,
      'carrera' => $datos_carrera,
      'materia' => $datos_materia,
      'secciones' => $datos_secciones);
     
    $this->load->view('formulario_encuesta', $data);
  }
  
  public function ingresar(){
    //verifico si se envio clave
    $pClave = $this->input->post('clave');
    if($pClave){
      $this->load->model('Clave');
      $this->load->model('Encuesta');
      //busco la clave ingresada
      $clave = $this->Encuesta->buscarClave($pClave);
      if ($clave){
        //si la clave no fue utilizada
        if ($clave->Utilizada == ''){
          $data = '';
          $this->load->view('formulario_encuesta', $data);
        }
        else{
          $data['clave'] = $pClave;
          $data['mensaje'] = "Clave de Acceso Utilizada el $clave->Utilizada.";
          $this->load->view('ingreso_clave', $data);
        }
      }
      else{
        $data['clave'] = $pClave;
        $data['mensaje'] = 'Clave de Acceso Inválida';
        $this->load->view('ingreso_clave', $data);
      }
    }
    else{
      $data['clave'] = '';
      $data['mensaje'] = '';
      $this->load->view('ingreso_clave', $data);
    }
    
  }
   
}

?>