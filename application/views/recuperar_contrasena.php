<!-- Última revisión: 2012-02-01 7:42 p.m. -->

<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Recuperar contraseña</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <form action="<?php echo site_url('usuarios/recuperarContrasena')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Recuperar la contraseña</h3>
            <label for="campoEmail">Ingrese su dirección de e-mail: <span class="opcional">*</span></label>
            <input id="campoEmail" type="email" name="email" value="<?php echo set_value('email', '')?>" required/>
            <?php echo form_error('email')?>
          </div>
        </div>
        <div class="row">
          <div class="three mobile-two columns centered pull-one-mobile">
            <input type="submit" class="button" name="submit" value="Aceptar" />
          </div>
        </div>
      </form>
    </div>

    <!-- Nav Sidebar -->
    <div class="three columns pull-nine">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
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
  
  <script>
    //ocultar mensaje de error al escribir
    $('input[type="email"]').keyup(function(){
      $(this).next('small.error').hide('fast');
    });
  </script>
</body>
</html>