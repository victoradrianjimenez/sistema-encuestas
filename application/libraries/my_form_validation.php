<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_Validation extends CI_Form_Validation {

  function __construct(){
    parent::__construct();
  }

  function alpha_dash_space($str){
    if (! preg_match("/^([-a-zA-Z0-9_ÁÉÍÓÚÑÜáéíóúñü, ])+$/i", $str)){
      $this->set_message('alpha_dash_space', 'El campo %s debe contener sólo letras, números y guiones.');
      return FALSE;
    }
    return TRUE;
  }
}
?> 