<?php

/**
 * 
 */
class pCharts extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }

  public function graficoPreguntaFacultad($idEncuesta, $idFormulario, $idPregunta){
    $this->load->model('Encuesta');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $this->Encuesta->idEncuesta = $idEncuesta;
    $this->Encuesta->idFormulario = $idFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaFacultad($idPregunta);
    $this->Pregunta = $this->gp->dame($idPregunta);
    switch($this->Pregunta->tipo){
    case 'S': case 'M':
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case 'N':
      $this->_generarGraficoNumerico($this->Pregunta, $datos_respuestas);
      break;
    }
  }

  public function graficoPreguntaDepartamento($idEncuesta, $idFormulario, $idPregunta, $idDepartamento){
    $this->load->model('Encuesta');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $this->Encuesta->idEncuesta = $idEncuesta;
    $this->Encuesta->idFormulario = $idFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaDepartamento($idPregunta, $idDepartamento);
    $this->Pregunta = $this->gp->dame($idPregunta);
    switch($this->Pregunta->tipo){
    case 'S': case 'M':
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case 'N':
      $this->_generarGraficoNumerico($this->Pregunta, $datos_respuestas);
      break;
    }
  }

  public function graficoPreguntaCarrera($idEncuesta, $idFormulario, $idPregunta, $idCarrera){
    $this->load->model('Encuesta');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $this->Encuesta->idEncuesta = $idEncuesta;
    $this->Encuesta->idFormulario = $idFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaCarrera($idPregunta, $idCarrera);
    $this->Pregunta = $this->gp->dame($idPregunta);
    switch($this->Pregunta->tipo){
    case 'S': case 'M':
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case 'N':
      $this->_generarGraficoNumerico($this->Pregunta, $datos_respuestas);
      break;
    }
  }

  public function graficoPreguntaMateria($idEncuesta, $idFormulario, $idPregunta, $idDocente, $idMateria, $idCarrera){
    $this->load->model('Encuesta');
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $this->Encuesta->idEncuesta = $idEncuesta;
    $this->Encuesta->idFormulario = $idFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaMateria($idPregunta, $idDocente, $idMateria, $idCarrera);
    $this->Pregunta = $this->gp->dame($idPregunta);
    switch($this->Pregunta->tipo){
    case 'S': case 'M':
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case 'N':
      $this->_generarGraficoNumerico($this->Pregunta, $datos_respuestas);
      break;
    }
  }

  /* 
   * Generar grafico de barras
   */
  function _generarGraficoOpciones($pregunta, $datos_respuestas){
    $this->load->model('Opcion');
    $opciones = $pregunta->listarOpciones();

    $pos = 0;
    $datos = array();
    $etiquetas = array();
    /*
    //incluir NC en los gráficos
    if ($pregunta->obligatoria=='N'){
      $datos[$pos] = ($datos_respuestas[0]['opcion'] == '')?$datos_respuestas[0]['cantidad']:'';
      $etiquetas[$pos] = 'NC';
      $pos++;
    }
    */
    foreach ($opciones as $i => $opcion) {
      $datos[$pos] = '';
      $etiquetas[$pos] = $opciones[$i]->texto;
      foreach ($datos_respuestas as $val) {
        if ($val['opcion'] == $opcion->idOpcion){
          $datos[$pos] = $val['cantidad'];
          break;
        }
      }
      $pos++;      
    }
    // Standard inclusions
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500, 160));
    
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
    
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(40,8,464,140);
    $this->pchart->drawGraphArea(255,255,254,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
     
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100);
    $this->pchart->Stroke();
  }

  /* 
   * Generar grafico de lineas
   */
  function _generarGraficoNumerico($pregunta, $datos_respuestas){
    $this->load->model('Opcion');

    $pos = 0;
    $datos = array();
    $etiquetas = array();
    /*
    //incluir NC en los gráficos
    if ($pregunta->obligatoria=='N'){
      $datos[$pos] = ($datos_respuestas[0]['opcion'] == '')?$datos_respuestas[0]['cantidad']:'';
      $etiquetas[$pos] = 'NC';
      $pos++;
    }
    */
    for ($i=1; $i <= (($pregunta->limiteSuperior - $pregunta->limiteInferior + $pregunta->paso) / $pregunta->paso); $i++) {
      $datos[$pos] = '';
      $etiquetas[$pos] = ($i-1) * $pregunta->paso + $pregunta->limiteInferior;
      foreach ($datos_respuestas as $val) {
        if ($val['opcion'] == $i){
          $datos[$pos] = $val['cantidad'];
          break;
        }
      }
      $pos++;      
    }
 
    // Standard inclusions
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500, 160));
    
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
    
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(40,8,464,140);
    $this->pchart->drawGraphArea(255,255,254,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
     
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100, TRUE);
    $this->pchart->Stroke();
  }

}

?>