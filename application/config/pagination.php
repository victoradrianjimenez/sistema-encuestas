<?php defined('BASEPATH') OR exit('No direct script access allowed');

//configuración para personalizar la barra de paginación (se la adapta para Foundation 3 Framework)
$config = array(
  'num_links' => 4,
  //contenedor
  'full_tag_open' => '<div class="pagination"><ul>',
  'full_tag_close' => '</ul></div>',
  //primera pagina
  'first_link' => '&laquo;',
  'first_tag_open' => '<li>',
  'first_tag_close' => '</li>',
  //ultima pagina
  'last_link' => '&raquo;',
  'last_tag_open' => '<li>',
  'last_tag_close' => '</li>',
  //pagina anterior
  'prev_link' => '&lt;',
  'prev_tag_open' => '<li>',
  'prev_tag_close' => '</li>',
  //pagina siguiente
  'next_link' => '&gt;',
  'next_tag_open' => '<li>',
  'next_tag_close' => '</li>',
  //pagina actual
  'cur_tag_open' => '<li class="active"><span>',
  'cur_tag_close' => '</span></li>',
  //pagina central
  'num_tag_open' => '<li>',
  'num_tag_close' => '</li>',
  'per_page' => PER_PAGE
  );
?>