<!DOCTYPE html>
<!-- Última revisión: 2012-02-05 11:47 p.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ver encuesta</title>
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
          <h3>Encuesta</h3>
          <h5>Período: <?php echo $encuesta->año.' ('.$encuesta->cuatrimestre.')'?></h5>
          <h5>Fecha de inicio de la toma de encuestas: <?php echo $encuesta->fechaInicio?></h5>
          <h5>Fecha de cierre de las encuestas: <?php echo $encuesta->fechaFin?></h5>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <ul class="button-group">
            <li><a class="button" data-reveal-id="modalGenerarClaves">Generar claves de acceso</a></li>
            <li><a class="button" data-reveal-id="">Ver claves generadas</a></li>
            <li><a class="button" data-reveal-id="modalFinalizar">Cerrar periodo de encuesta</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <!-- ventana modal para generar claves de acceso -->
  <div id="modalGenerarClaves" class="reveal-modal medium">
    <?php  
      //include 'elements/form-alta-claves.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>

  <!-- ventana modal para desasociar materias a la carrera -->
  <div id="modalFinalizar" class="reveal-modal medium">
    <form action="<?php echo site_url('encuestas/finalizar')?>" method="post">
      <h3>Finalizar período de encuesta</h3>
      <h5><?php echo $encuesta->año.' ('.$encuesta->cuatrimestre.')'?></h5>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
      <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
      <div class="row">         
        <div class="ten columns centered">
          <div class="six mobile-one columns push-one-mobile">
            <input class="button cancelar" type="button" value="Cancelar"/>
          </div>
          <div class="six mobile-one columns pull-one-mobile ">
            <input class="button" type="submit" name="submit" value="Aceptar" />
          </div>
        </div>
      </div>
    </form>
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