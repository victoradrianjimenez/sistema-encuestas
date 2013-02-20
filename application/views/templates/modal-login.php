<!-- Modal -->
<div id="LoginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Iniciar Sesión</h3>
  </div>
  <form id="login" class="form-horizontal" action="<?php echo site_url("usuarios/login")?>" method="post"> 
    <div class="modal-body">
      <input type="hidden" name="redirect" value="<?php if(isset($redirectLogin)) echo $redirectLogin?>" />
      <div class="control-group">
        <label class="control-label" for="loginUsuario">Usuario</label>
        <div class="controls">
          <input id="loginUsuario" type="text" name="usuario" required />
          <?php echo form_error('usuario')?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="loginContraseña">Contraseña</label>
        <div class="controls">
          <input id="loginContraseña" type="password" name="contrasena" required />
          <?php echo form_error('contrasena')?>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <label class="checkbox">
            <input type="checkbox" name="Recordarme" />Recordarme   
          </label>
          <a href="<?php echo site_url("usuarios/recuperarContrasena")?>">¿Olvidó la contraseña?</a>
        </div>
      </div>
      <?php if(isset($mensajeLogin)):?>
        <div class="alert-box alert")>
          <?php echo $mensajeLogin ?>
          <a href="#" class="close">&times;</a>
        </div>
      <?php endif ?>  
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <input type="submit" name="sumit" value="Aceptar" class="btn btn-primary" />
    </div>
  </form>
</div>  