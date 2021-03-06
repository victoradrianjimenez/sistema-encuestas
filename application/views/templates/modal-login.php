<!-- Modal -->
<div id="LoginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Iniciar Sesión</h3>
  </div>
  <form id="login" class="form-horizontal" action="<?php echo site_url("usuarios/login")?>" method="post"> 
    <div class="modal-body">
      <div class="control-group">
        <label class="control-label" for="loginUsuario">Usuario</label>
        <div class="controls">
          <input id="loginUsuario" type="text" name="usuarioLogin" required />
          <?php echo form_error('usuario')?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="loginContraseña">Contraseña</label>
        <div class="controls">
          <input id="loginContraseña" type="password" name="contrasenaLogin" required />
          <?php echo form_error('contrasena')?>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <label class="checkbox">
            <input type="checkbox" name="recordarmeLogin" value="1" checked/>Recordarme   
          </label>
          <a href="<?php echo site_url("usuarios/recuperarContrasena")?>">¿Olvidó la contraseña?</a>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="submit" name="submit" value="Aceptar" class="btn btn-primary" />
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    </div>
  </form>
</div>
<?php if(isset($showLogin)) echo'<script>window.onload=function(){$("#LoginModal").modal();}</script>'//abrir ventana de login?>