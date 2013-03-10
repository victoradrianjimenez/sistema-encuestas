<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Cambiar contraseña - <?php echo NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <br />
      <div class="modals span8 offset2" style="position:static">
        <form class="form-horizontal" action="<?php echo site_url('usuarios/resetearContrasena/'.$code)?>" method="post">
          <input type="hidden" name="user_id" value="<?php echo $user_id?>" />
          <input type="hidden" <?php echo 'name="'.$csrf[0].'" value="'.$csrf[1].'"'?> />
          <div class="modal-header">
            <h3>Cambiar la contraseña</h3>
          </div>
          <div class="modal-body">
            <div class="control-group">
              <label class="control-label" for="campoContraseña">Nueva contraseña: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña" type="password" name="nuevaContrasena" value="<?php echo set_value('nuevaContrasena', '')?>" required />
                <?php echo form_error('nuevaContrasena')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoContraseña2">Confirmar contraseña: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña2" type="password" name="confirmarContrasena" required />
                <?php echo form_error('confirmarContrasena')?>
              </div>
            </div>
            <!-- Botones -->
            <div class="control-group">
              <div class="controls">
                <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
  <?php include 'templates/footer.php'?>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
</body>
</html>