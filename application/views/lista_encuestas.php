<!DOCTYPE html>
<!-- Última revisión: 2012-02-05 11:27 a.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Encuestas</title>
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
          <h3>Encuestas</h3>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron encuestas.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Año / Periodo</th>
                <th>Fecha inicio</th>
                <th>Fecha cierre</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a href="<?php echo site_url("encuestas/ver/".$item->idEncuesta.'/'.$item->idFormulario)?>">
                    <?php echo $item->año.' / '.$item->cuatrimestre?>
                  </a></td>
                  <td><?php echo $item->fechaInicio?></td>
                  <td><?php echo $item->fechaFin?></td>
                  <td>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="six mobile-two columns pull-one-mobile">
          <a class="button" data-reveal-id="modalNueva">Nueva Encuesta</a>
        </div>       
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  
  <!-- ventana modal para agregar una materia -->
  <div id="modalNueva" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      include 'elements/form-editar-encuesta.php'; 
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