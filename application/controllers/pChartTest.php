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
    $this->load->library('pChart/pChart', array(700, 230));

   // Dataset definition 
   $this->pdata->AddPoint(array(1,4,-3,2,-3,3,2,1,0,7,4,-3,2,-3,3,5,1,0,7),"Serie1");
   $this->pdata->AddPoint(array(0,3,-4,1,-2,2,1,0,-1,6,3,-4,1,-4,2,4,0,-1,6),"Serie2");
   
   $this->pdata->AddAllSeries();
   $this->pdata->SetAbsciseLabelSerie();
   $this->pdata->SetSerieName("January","Serie1");
   $this->pdata->SetSerieName("February","Serie2");
  
   // Initialise the graph
   $this->pchart->setFontProperties("Fonts/tahoma.ttf",8);
   $this->pchart->setGraphArea(50,30,585,200);
   $this->pchart->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
   $this->pchart->drawRoundedRectangle(5,5,695,225,5,230,230,230);
   $this->pchart->drawGraphArea(255,255,255,TRUE);
   $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
   $this->pchart->drawGrid(4,TRUE,230,230,230,50);
  
   // Draw the 0 line
   $this->pchart->setFontProperties("Fonts/tahoma.ttf",6);
   $this->pchart->drawTreshold(0,143,55,72,TRUE,TRUE);
  
   // Draw the bar graph
   $this->pchart->drawOverlayBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription());
  
   // Finish the graph
   $this->pchart->setFontProperties("Fonts/tahoma.ttf",8);
   $this->pchart->drawLegend(600,30,$this->pdata->GetDataDescription(),255,255,255);
   $this->pchart->setFontProperties("Fonts/tahoma.ttf",10);
   $this->pchart->drawTitle(50,22,"Example 3",50,50,50,585);
   $this->pchart->stroke("example3.png");

  }
  
}

?>