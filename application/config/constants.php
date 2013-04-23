<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

/*
|--------------------------------------------------------------------------
| Docment root folders
|--------------------------------------------------------------------------
|
| These constants use existing location information to work out web root, etc.
|
*/

// Base URL (keeps this crazy sh*t out of the config.php
if (isset($_SERVER['HTTP_HOST']))
{
    $base_url  = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
    $base_url .= '://'. $_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    // Base URI (It's different to base URL!)
    $base_uri = parse_url($base_url, PHP_URL_PATH);

    if (substr($base_uri, 0, 1) != '/')
    {
        $base_uri = '/'.$base_uri;
    }

    if (substr($base_uri, -1, 1) != '/')
    {
        $base_uri .= '/';
    }
}

else
{
    $base_url = 'http://localhost/';
    $base_uri = '/';
}

// Define these values to be used later on
define('BASE_URL', $base_url);
define('BASE_URI', $base_uri);
define('APPPATH_URI', BASE_URI.APPPATH);

// We dont need these variables any more
unset($base_uri, $base_url); 


/*
|--------------------------------------------------------------------------
| Constantes del Sistema de Encuestas
|--------------------------------------------------------------------------
|
| Estas constantes son propias del sistema
|
*/

//delimitadores de los errores de validación de formularios
define('ERROR_DELIMITER_START', '<span class="label label-important">');
define('ERROR_DELIMITER_END', '</span>');

//nombres de clases HTML para cada tipo de error 
define('ALERT_WARNING', 'alert-block');
define('ALERT_ERROR', 'alert-error');
define('ALERT_INFO', 'alert-info');
define('ALERT_SUCCESS', 'alert-success');

define('TIPO_ANONIMA', 'A');
define('TIPO_REGISTRO', 'R');
define('TIPO_OBLIGATORIA', 'O');

define('TIPO_SELECCION_SIMPLE', 'S');
define('TIPO_NUMERICA', 'N');
define('TIPO_TEXTO_SIMPLE', 'T');
define('TIPO_TEXTO_MULTILINEA', 'X');

define('SECCION_TIPO_NORMAL', 'N');
define('SECCION_TIPO_DOCENTE', 'D');
define('SECCION_TIPO_ALUMNO', 'A');

define('TIPO_ACCESO_DOCENTE', 'D');
define('TIPO_ACCESO_JEFE_CATEDRA', 'J');

define('PROCEDURE_SUCCESS', 'ok');

define('RESPUESTA_SI', 'S');
define('RESPUESTA_NO', 'N');

define('MODO_INDICE_INVERSO', 'I'); //el indice calculado es mas favorables para la primera opcion de la pregunta
define('MODO_INDICE_NORMAL', 'S'); //el indice calculado es mas favorables para la ultima opcion de la pregunta
define('MODO_INDICE_NULO', 'N'); //la pregunta no influye en el cálculo del índice
?>