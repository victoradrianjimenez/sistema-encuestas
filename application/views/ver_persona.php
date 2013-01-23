<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ver Persona</title>
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
    <!-- Main -->
    <div id="Main" class="nine columns push-three">
      <form class="custom" action="<?php echo $link?>" method="post"> 
        <fieldset>
          <legend>Persona</legend>
          <input type="hidden" name="IdPersona" value="<?php echo $persona['IdPersona']?>"/>
          <div class="twelve columns">
            <label for="campoNombre">Nombre: </label>
            <input id="campoNombre" type="text" name="Nombre" value="<?php echo $persona['Nombre']?>"/>
            <?php echo form_error('Nombre'); ?>
            
            <label for="campoApellido">Apellido: </label>
            <input id="campoApellido" type="text" name="Apellido" required value="<?php echo $persona['Apellido']?>"/>
            <?php echo form_error('Nombre'); ?>
            
            <label for="campoEmail">Dirección de correo electrónico: </label>
            <input id="campoEmail" type="text" name="Email" required value="<?php echo $persona['Email']?>"/>
            <?php echo form_error('Email'); ?>
            
            <label for="campoUsuario">Nombre de usuario: </label>
            <input id="campoUsuario" type="text" name="Usuario" required value="<?php echo $persona['Usuario']?>"/>
            <?php echo form_error('Usuario'); ?>
            
            <label for="campoContraseña">Contraseña: </label>
            <input id="campoContraseña" type="password" name="Contrasena" required value="<?php echo $persona['Contraseña']?>"/>
            <?php echo form_error('Contrasena'); ?>
            
            <label for="campoContraseña2">Repetir la contraseña: </label>
            <input id="campoContraseña2" type="password" name="Contrasena2" required/>
            <?php echo form_error('Contrasena2'); ?>
          </div>
          <div class="row">         
            <div class="six columns centered">
              <div class="six mobile-one columns push-one-mobile">
                <button id="botonVolver" class="button">Cancelar</button>
              </div>
              <div class="six mobile-one columns pull-one-mobile ">
                <input class="button" type="submit" name="submit" value="Aceptar" />
              </div>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    
    <!-- Nav Sidebar -->
    <!-- This is source ordered to be pulled to the left on larger screens -->
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
  
  <script language="JavaScript">
    //ocultar mensajes de errores de formulario de CodeIgniter, cuando se presiona una tecla en el input
    $('input[type="text"], input[type="password"]').keypress(function(){
      $(this).next('small.error').hide('fast', function(){$(this).remove();});
    });
    //funcionalidad del boton volver atras
    $('#botonVolver').click(function(){
      window.history.back();
      return false;
    });
  </script>
</body>
</html>