<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_Validation extends CI_Form_Validation {

  function __construct(){
    parent::__construct();
  }

  function alpha_dash_space($str){
    return ( ! preg_match("/^([-a-z0-9_ ])+$/i", $str)) ? FALSE : TRUE;
  }
}
?> 