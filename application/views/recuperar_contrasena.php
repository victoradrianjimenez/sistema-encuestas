<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Recuperar contraseña - <?php echo NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <br />
      <div class="modals span8 offset2" style="position:static">
        <form class="form-horizontal" action="<?php echo site_url('usuarios/recuperarContrasena')?>" method="post">
          <div class="modal-header">
            <h3>Recuperar la contraseña</h3>
          </div>
          <div class="modal-body">
            <div class="control-group">
              <label class="control-label" for="campoEmail">Dirección de e-mail: </label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="email" name="email" value="<?php echo set_value('email', '')?>" required/>
                <?php echo form_error('email')?>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <input type="submit" class="btn btn-primary" name="submit" value="Aceptar" />
              </div>
            </div>
          </div>
        </form>
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
  <script src="<?php echo base_url('js/formularios.js')?>"></script> 
</body>
</html>