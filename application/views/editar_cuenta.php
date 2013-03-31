<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar datos de usuario - <?php echo NOMBRE_SISTEMA?></title>
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
          <img src="<?php echo site_url('usuarios/imagen/'.$usuarioLogin->idImagen)?>" width="150" height="150" alt="Imagen de usuario"/>
        </div>
      </div>
      
      <div class="row">
        <!-- Main -->
        <div class="span12">
          <form action="<?php echo site_url('usuarios/modificarCuenta')?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $usuarioLogin->id?>"/>
            <div class="control-group">
              <div class="controls">
                <h4>Datos de Usuario</h4>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="campoEmail">E-mail: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="text" name="email" maxlength="100" required value="<?php echo $usuarioLogin->email?>"/>
                <?php echo form_error('email'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoUsuario">Nombre de usuario: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoUsuario" type="text" name="username" maxlength="100" required value="<?php echo $usuarioLogin->username?>"/>
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
              <label class="control-label" for="campoImagen">Subir una imágen (tamaño máximo 500KB): </label>
              <div class="controls">
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                <input id="campoImagen" type="file" name="imagen"/>
                <?php echo form_error('imagen'); ?>
                <label class="checkbox"><input type="checkbox" name="noImagen" value="1" <?php echo ($noImagen)?'checked="checked"':''?> />Eliminar imagen actual</label>
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
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
</body>
</html>  
