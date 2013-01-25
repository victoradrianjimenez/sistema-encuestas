<?php

/**
 * 
 */
class pCharts extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){

    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500,160));
    
    // Dataset definition 
   $this->pdata->AddPoint(array(1,2,3,4, 8),"Serie1");
   $this->pdata->AddPoint(array("No contesta", "Nunca", "A veces", "Casi siempre", "Siempre"),"AbsciseLabels");
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
    $this->pchart->Stroke();
    
    

  }

  public function graficoPregunta($IdEncuesta, $IdFormulario, $IdPregunta, $IdDocente, $IdMateria, $IdCarrera){
    // Standard inclusions   
    $this->load->model('Encuesta');
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500,160));
    
    $this->Encuesta->IdEncuesta = $IdEncuesta;
    $this->Encuesta->IdFormulario = $IdFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaMateria($IdPregunta, $IdDocente, $IdMateria, $IdCarrera);
    //$datos = array(1,1,1,3);
    //$etiquetas = array(1,2,3,4);
    foreach ($datos_respuestas as $i => $val) {
      $datos[$i] = $val['Cantidad'];
      $etiquetas[$i] = ($val['Texto']!='')?$val['Texto']:'NC';
    }
   
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
    
     // Inicializar gráfico
    $this->pchart->setFontProperties("fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(36,8,464,140);
    $this->pchart->drawGraphArea(255,255,254,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
     
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100);
    $this->pchart->Stroke();
  }
}

?>