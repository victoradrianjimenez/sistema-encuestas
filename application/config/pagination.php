<?php defined('BASEPATH') OR exit('No direct script access allowed');

//configuración para personalizar la barra de paginación (se la adapta para Foundation 3 Framework)
$config = array(
  'num_links' => 2,
  //contenedor
  'full_tag_open' => '<ul class="pagination">',
  'full_tag_close' => '</ul>',
  //primera pagina
  'first_link' => '&laquo;',
  'first_tag_open' => '<li class"arrow">',
  'first_tag_close' => '</li>',
  //ultima pagina
  'last_link' => '&raquo;',
  'last_tag_open' => '<li class"arrow">',
  'last_tag_close' => '</li>',
  //pagina anterior
  'prev_link' => '&lt;',
  'prev_tag_open' => '<li class="arrow">',
  'prev_tag_close' => '</li>',
  //pagina siguiente
  'next_link' => '&gt;',
  'next_tag_open' => '<li class="arrow">',
  'next_tag_close' => '</li>',
  //pagina actual
  'cur_tag_open' => '<li class="current"><a>',
  'cur_tag_close' => '</a></li>',
  //pagina central
  'num_tag_open' => '<li>',
  'num_tag_close' => '</li>'
  );

?>