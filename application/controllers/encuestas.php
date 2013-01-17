<?php

/**
 * 
 */
class Encuestas extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('index.php', $data);
  }
  
  
  public function informeMateria(){
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Carrera');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_formularios','gf');
    $this->load->model('Gestor_carreras','gc');
    $this->Encuesta->IdEncuesta = 1;
    $this->Encuesta->IdFormulario = 1;
    $formulario = $this->gf->dame(1);
    $carrera = $this->gc->dame(1);
    
    $secciones = $formulario->listarSeccionesCarrera(1);
    
    echo $formulario->Nombre;
    echo $carrera->Nombre;
    
    
    $datos_secciones = array(); 
    foreach ($secciones as $i => $seccion) {
      $items = $seccion->listarItems();
      $datos_items = array();
      foreach ($items as $j => $item) {
        $opciones = $item->listarOpciones();
        $datos_opciones = array();
        foreach ($opciones as $k => $opcion) {
          $datos_opciones[$k] = array(
            'idOpcion' => $opcion->IdOpcion,
            'texto' => $opcion->Texto
            );
        }
        $datos_respuestas = $this->Encuesta->respuestasPreguntaMateria($item->IdPregunta, 5, 5);
        $datos_items[$j] = array(
          'idPregunta' => $item->IdPregunta,
          'texto' => $item->Texto,
          'tipo' => $item->Tipo,
          'opciones' => $datos_opciones,
          'respuestas' => $datos_respuestas 
          );
      }
      $datos_secciones[$i] =  array(
        'texto' => $seccion->Texto,
        'preguntas' => $datos_items
        );
    
    }
    
    $datos['secciones'] = $datos_secciones;
    $newdata = array(
                   'encuesta'  => serialize($this->Encuesta),
                   'idCarrera'     => 5,
                   'idMateria'     => 5
               );

    $this->session->set_userdata($newdata);
    
    
    
    $this->load->view('informe_materia', $datos);
  }




  public function graficoPregunta($IdEncuesta, $IdFormulario, $IdPregunta, $IdMateria, $IdCarrera){
    $this->load->model('Encuesta');
    $encuesta = unserialize($this->session->userdata('encuesta'));
    
    $IdEncuesta = $encuesta->IdEncuesta;
    $IdFormulario = $encuesta->IdFormulario;   
    $IdMateria = $this->session->userdata('idMateria');
    $IdCarrera = $this->session->userdata('idCarrera'); 
    
    // Standard inclusions   
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500,160));
    
    $this->Encuesta->IdEncuesta = $IdEncuesta;
    $this->Encuesta->IdFormulario = $IdFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaMateria($IdPregunta, $IdMateria, $IdCarrera);
    $datos = array();
    $etiquetas = array();
    foreach ($datos_respuestas as $i => $val) {
      $datos[$i] = $val['Cantidad'];
      $etiquetas[$i] = $val['Opcion'];
    }
    
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
    
     // Inicializar gráfico
    $this->pchart->setFontProperties("Fonts/tahoma.ttf",10);
    $this->pchart->setGraphArea(36,8,464,140);
    $this->pchart->drawGraphArea(255,255,255,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
     
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100);
    $this->pchart->Stroke("example20.png");
  }
  
}

?>