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
    case TIPO_SELECCION_SIMPLE:
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case TIPO_NUMERICA:
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
    case TIPO_SELECCION_SIMPLE:
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case TIPO_NUMERICA:
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
    case TIPO_SELECCION_SIMPLE:
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case TIPO_NUMERICA:
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
    case TIPO_SELECCION_SIMPLE:
      $this->_generarGraficoOpciones($this->Pregunta, $datos_respuestas);
      break;
    case TIPO_NUMERICA:
      $this->_generarGraficoNumerico($this->Pregunta, $datos_respuestas);
      break;
    }
  }

  /* 
   * Generar grafico de barras para las preguntas con opciones
   */
  function _generarGraficoOpciones($pregunta, $datos_respuestas){
    $this->load->model('Opcion');
    $opciones = $pregunta->listarOpciones();
    $pos = 0;
    $datos = array();
    $etiquetas = array();
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
    $ancho = 550;
    $alto = 150;
    // Standard inclusions
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array($ancho, $alto));
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(40,8,$ancho-36,$alto-20);
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
   * Generar grafico de lineas (preguntas del tipo numerica)
   */
  function _generarGraficoNumerico($pregunta, $datos_respuestas){
    $this->load->model('Opcion');
    $pos = 0;
    $datos = array();
    $etiquetas = array();
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
    $ancho = 550;
    $alto = 150;
    // Standard inclusions
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array($ancho, $alto));
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(40,8,$ancho-36,$alto-20);
    $this->pchart->drawGraphArea(255,255,254,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100, TRUE);
    $this->pchart->Stroke();
  }

  
  public function graficoHistoricoMateria($idMateria, $idCarrera, $idPregunta, $fechaInicio, $fechaFin){
    $this->load->model('Pregunta');
    $this->load->model('Gestor_preguntas','gp');
    $pregunta = $this->gp->dame($idPregunta);
    $historico = $pregunta->historicoMateria($idMateria, $idCarrera, $fechaInicio, $fechaFin);
    if (!empty($historico)){
      $this->_generarHistorico($historico);
    }
  }

  /* 
   * Generar grafico de lineas (historicos)
   */
  function _generarHistorico($historico){
    $minAño = $historico[0]['año'];
    $maxAño = $historico[0]['año'];
    $maxPeriodo = 0;
    $promedios = array();
    foreach ($historico as $i => $fila) {
      $año = $fila['año'];
      $cuatrimestre = $fila['cuatrimestre'];
      $promedios[$año.$cuatrimestre] = $fila['promedio'];
      if ($cuatrimestre > $maxPeriodo){
        $maxPeriodo = $cuatrimestre;
      }
      if ($año > $maxAño){
        $maxAño = $año;
      }
      elseif ($año < $minAño){
        $minAño = $año;
      }
    }
    $cnt = 0;
    $datos = array();
    $etiquetas = array();
    for($i=$minAño; $i<=$maxAño; $i++){
      for($j=1; $j<=$maxPeriodo; $j++){
        $etiquetas[$cnt] = $i.'/'.$j;
        $datos[$cnt] = (isset($promedios[$i.$j])) ? $promedios[$i.$j] : '';
        $cnt++;
      }
    }
    //$datos = array(10,4,3,2,3,3,2,1,0,7,4,3,2,3,3,5,1,0,7);
    //$etiquetas = array(1,4,2,6,2,3,0,1,-5,1,2,4,5,2,1,0,6,4,30);

    $ancho = 600;
    $alto = 200;
    // Standard inclusions
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array($ancho, $alto));
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",10);
    $this->pchart->setGraphArea(40,8,$ancho-36,$alto-60);
    $this->pchart->drawGraphArea(255,255,254,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,90,2,TRUE); 
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawLineGraph($this->pdata->GetData(),$this->pdata->GetDataDescription());
    $this->pchart->drawPlotGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),3,2,255,255,255);
    $this->pchart->Stroke();
  }
}

?>