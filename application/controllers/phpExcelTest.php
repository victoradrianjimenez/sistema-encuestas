<?php

/**
 * 
 */
class phpExcelTest extends CI_Controller{
  
  function __construct() {
    parent::__construct();
    $this->load->library(array('session', 'ion_auth', 'form_validation'));
  }
  
  public function index(){
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Europe/London');
    
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    
    /** Include PHPExcel */
    $this->load->library('PHPExcel/PHPExcel');
    
    // Create new PHPExcel object
    echo date('H:i:s') , " Create new PHPExcel object" , EOL;
    $objPHPExcel = $this->phpexcel;
    
    // Set document properties
    echo date('H:i:s') , " Set document properties" , EOL;
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    							 ->setLastModifiedBy("Maarten Balliauw")
    							 ->setTitle("PHPExcel Test Document")
    							 ->setSubject("PHPExcel Test Document")
    							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
    							 ->setKeywords("office PHPExcel php")
    							 ->setCategory("Test result file");
    
    
    // Add some data
    echo date('H:i:s') , " Add some data" , EOL;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Hello')
                ->setCellValue('B2', 'world!')
                ->setCellValue('C1', 'Hello')
                ->setCellValue('D2', 'world!');
    
    // Miscellaneous glyphs, UTF-8
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A4', 'Miscellaneous glyphs')
                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
    
    // Rename worksheet
    echo date('H:i:s') , " Rename worksheet" , EOL;
    $objPHPExcel->getActiveSheet()->setTitle('Simple');
    
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    
    
    // Save Excel 2007 file
    echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
    echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
    // Save Excel5 file
    echo date('H:i:s') , " Write to Excel5 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save(str_replace('.php', '.xls', __FILE__));
    echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
    
    
    // Echo memory peak usage
    echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;
    
    // Echo done
    echo date('H:i:s') , " Done writing files" , EOL;
    echo 'Files have been created in ' , getcwd() , EOL;
  }
}

?>