<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar datos de usuario</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Editar datos de cuenta de usuario</h3>
          <p>Nombre: <?php echo $usuarioLogin->nombre.' '.$usuarioLogin->apellido?></p>
        </div>
      </div>
      
      <div class="row">
        <!-- Main -->
        <div class="span12">
          <form class="form-horizontal" action="<?php echo site_url('usuarios/modificarCuenta')?>" method="post">
            <input type="hidden" name="id" value="<?php echo $usuarioLogin->id?>"/>
            <div class="control-group">
              <div class="controls">
                <h4>Datos de Usuario</h4>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="campoEmail">E-mail: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="text" name="email" required value="<?php echo $usuarioLogin->email?>"/>
                <?php echo form_error('email'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoUsuario">Nombre de usuario: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoUsuario" type="text" name="username" required value="<?php echo $usuarioLogin->username?>"/>
                <?php echo form_error('username'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoContraseña">Contraseña: </label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña" type="password" name="password" value=""/>
                <?php echo form_error('password'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoContraseña2">Confirmar contraseña: </label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña2" type="password" name="password2" value=""/>
                <?php echo form_error('password2'); ?>
              </div>
            </div>
            
            <div class="control-group">
              <div class="controls">
                <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>

  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script>
    $('input[type="text"], input[type="password"]').keyup(function(){
      $(this).siblings('span.label').hide('fast');
    });
  </script>
</body>
</html>  
