<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends TCPDFa{
    function __construct(){
        parent::__construct();
        $l = array();
        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'es';
        $l['w_page'] = 'página';
        $this->setLanguageArray($l);
    }
}
?>