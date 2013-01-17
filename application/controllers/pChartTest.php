<?php

/**
 * 
 */
class pChartTest extends CI_Controller{
  
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
    $this->pchart->Stroke("example20.png");
    
    

  }
  
}

?>