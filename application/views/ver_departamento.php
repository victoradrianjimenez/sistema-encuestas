<!DOCTYPE html>
<!-- Última revisión: 2012-01-31 11:23 a.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ver departamento</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>
  
  <!-- Main Section -->
  <div class="row">
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div> 
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <div class="row">
        <div class="twelve columns">
          <h3><?php echo $departamento->nombre?></h3>
          <p>Jefe de Departamento: <?php echo $jefeDepartamento->nombre.' '.$jefeDepartamento->apellido?></p>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <ul class="button-group">
            <li><a class="button" data-reveal-id="modalModificar">Modificar departamento...</a></li>
            <li><a class="button" href="<?php echo site_url('departamentos')?>">Todos los departamentos</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <!-- ventana modal para editar datos de la carrera -->
  <div id="modalModificar" class="reveal-modal medium">
    <?php
      $titulo = 'Editar departamento';
      $link = site_url('departamentos/modificar'); //a donde mandar los datos editados para darse de alta  
      include 'elements/form-editar-departamento.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
  
  <script>
    $('.cancelar').click(function(){
      $(this).trigger('reveal:close'); //cerrar ventana
    });
    
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('small.error').parentsUntil('.reveal-modal').parent().first().reveal();
  </script>
</body>
</html>