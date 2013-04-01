<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['publicarInformes'] = FALSE;
$config['publicarHistoricos'] = FALSE;

$config['captchaExpiration'] = 7200;
$config['captchaFont'] = 'fonts/comic.ttf';

//delimitadores de los errores de validación de formularios
define('NOMBRE_FACULTAD', 'Facultad de Ciencias Exactas y Tecnología');
define('NOMBRE_UNIVERSIDAD', 'Universidad Nacional de Tucumán');

//cuantos items se mostraran por pagina en un listado
define('PER_PAGE', 10);

//periodos cada cuanto se toma una encuesta
define('PERIODO', 'Cuatrimestre');
define('NOMBRE_SISTEMA', 'Sistema Encuestas');

?>