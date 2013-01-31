<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ver usuario</title>
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
      <div class="row">
        <div class="twelve columns">
          <h3><?php echo $usuario['nombre'].' '.$usuario['apellido']?></h3>
          <h5>Email: <?php echo $usuario['email']?></h5>
          <h5>Último acceso: <?php echo $usuario['last_login']?></h5>
          <h5>Estado: <?php echo ($usuario['active'])?'Activo':'Inactivo'?></h5>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <ul class="button-group">
            <li><a class="button" data-reveal-id="modalModificar">Modificar usuario...</a></li>
            <li><a class="button" data-reveal-id="modalActivar">Activar/Desactivar cuenta</a></li>
          </ul>
        </div>
      </div>
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
    
  <!-- ventana modal para editar datos del usuario -->
  <div id="modalModificar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('usuarios/modificar');
      $titulo = 'Editar usuario';
      include 'elements/form-editar-usuario.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>

  <!-- ventana modal para activar/desactivar una cuenta de usuario -->
  <div id="modalActivar" class="reveal-modal small">
    <form action="<?php echo site_url(($usuario['active'])?'usuarios/desactivar':'usuarios/activar')?>" method="post">
      <h3><?php echo ($usuario['active'])?'Desactivar cuenta de usuario':'Activar cuenta de usuario'?></h3>
      <h5 class="nombre"></h5>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="id" value="<?php echo $usuario['id']?>" />
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
  
  <script language="JavaScript">
    $('.cancelar').click(function(){
      $(this).trigger('reveal:close'); //cerrar ventana
    });
  </script>
</body>
</html>