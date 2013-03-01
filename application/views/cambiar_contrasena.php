<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Cambiar contraseña</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Main -->
        <div class="span12">
          <h4>Cambiar la contraseña</h4>
          <form action="<?php echo site_url('usuarios/resetearContrasena/'.$code)?>" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user_id?>" />
            <input type="hidden" <?php echo 'name="'.$csrf[0].'" value="'.$csrf[1].'"'?> />
            <div class="control-group">
              <label for="campoContraseña">Nueva contraseña: <span class="opcional">*</span></label>
              <div class="controls">
                <input id="campoContraseña" type="password" name="nuevaContrasena" value="<?php echo set_value('nuevaContrasena', '')?>" required />
                <?php echo form_error('nuevaContrasena')?>
              </div>
            </div>
            <div class="control-group">
              <label for="campoContraseña2">Confirmar contraseña: <span class="opcional">*</span></label>
              <div class="controls">
                <input id="campoContraseña2" type="password" name="confirmarContrasena" required />
                <?php echo form_error('confirmarContrasena')?>
              </div>
            </div>
            <div>
              <input class="btn btn-primary" type="submit" class="button" name="submit" value="Aceptar" />
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