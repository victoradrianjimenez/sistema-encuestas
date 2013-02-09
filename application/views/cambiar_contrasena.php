<!DOCTYPE html>
<!-- Última revisión: 2012-02-01 7:45 p.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Cambiar contraseña</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>  
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <form action="<?php echo site_url('usuarios/resetearContrasena/'.$code)?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Cambiar la contraseña</h3>      
            <label for="campoContraseña">Nueva contraseña: <span class="opcional">*</span></label>
            <input id="campoContraseña" type="password" name="nuevaContrasena" value="<?php echo set_value('nuevaContrasena', '')?>" required />
            <?php echo form_error('nuevaContrasena')?>
            
            <label for="campoContraseña2">Confirmar contraseña: <span class="opcional">*</span></label>
            <input id="campoContraseña2" type="password" name="confirmarContrasena" required />
            <?php echo form_error('confirmarContrasena')?>
            
            <input type="hidden" name="user_id" value="<?php echo $user_id?>" />
            <input type="hidden" <?php echo 'name="'.$csrf[0].'" value="'.$csrf[1].'"'?> />
          </div>
        </div>    
        <div class="row">              
          <div class="three mobile-two columns centered pull-one-mobile">
            <input type="submit" class="button" name="submit" value="Aceptar" />
          </div>
        </div>
      </form>
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
    $('input[type="password"]').keyup(function(){
      $(this).next('small.error').hide('fast');
    });
  </script>
</body>
</html>