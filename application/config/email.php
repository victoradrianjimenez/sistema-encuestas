<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_port'] = '465';
$config['smtp_user'] = 'encuestas.facet@gmail.com';
$config['smtp_pass'] = 'encuestas123';
$config['smtp_timeout'] = '7';
$config['charset']    = 'utf-8';
$config['newline']    = "\r\n";
$config['mailtype'] = 'html';
$config['validation'] = TRUE;
?>