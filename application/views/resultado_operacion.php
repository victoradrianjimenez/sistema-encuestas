<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?>
  <title>Resultado de la operación</title>
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
    
    <!-- Main -->
    <div  id="Main" class="nine columns">
      <div class="twelve columns">
        <h4>Resultado de la operación:</h4>
        <p><?php echo $mensaje ?></p>
      </div>
      <div class="two mobile-two columns">
        <a id="botonVolver" class="button">Volver</a>
      </div>
      <div class="ten mobile-two columns pull-one-mobile">
        <a class="button" href="<?php echo $link ?>">Aceptar</a>
      </div>
    </div>
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>

  <script language="JavaScript">
    //funcionalidad del boton volver atras
    $('#botonVolver').click(function(){
      window.history.back();
      return false;
    });
  </script>  
</body>
</html>