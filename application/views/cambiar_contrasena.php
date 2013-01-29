<!DOCTYPE html>

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
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <div class="row">
        <div class="twelve columns">
          <h3>Cambiar la contraseña</h3>
          <form action="<?php echo site_url('usuarios/resetearContrasena/'.$code)?>" method="post">
            <label>Nueva contraseña:</label>
            <input type="password" name="NuevaContrasena" required />
            <label>Confirmar contraseña:</label>
            <input type="password" name="ConfirmarContrasena" required />
            <input type="hidden" name="user_id" value="<?php echo $user_id?>" />
            <input type="hidden" <?php echo 'name="'.$csrf[0].'" value="'.$csrf[1].'"'?> />
            <input type="submit" class="button" name="submit" value="Aceptar" />
          </form>
        </div>
      </div>
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
</body>
</html>