<!-- Última revisión: 2012-02-01 6:18 p.m. -->

<?php if(!(isset($usuarioLogin) && is_object($usuarioLogin))): ?>
  <form id="login" action="<?php echo site_url("usuarios/login")?>" method="post"> 
    <fieldset>
      <legend>Iniciar sesión</legend>
      <div class="row">
        <label for="loginUsuario">Usuario</label>
        <input id="loginUsuario" type="text" name="usuario" required />
        <?php echo form_error('usuario')?>
        <label for="loginContraseña">Contraseña</label>
        <input id="loginContraseña" type="password" name="contrasena" required />
        <?php echo form_error('contrasena')?>
      </div>
      <div class="row">
        <input type="checkbox" name="Recordarme" />Recordarme              
      </div>
      <div class="row">
        <div class="twelve mobile-one columns pull-three-mobile">
          <input type="submit" name="sumit" value="Enviar" class="small button" />
        </div>
      </div>
      <?php if(isset($mensajeLogin)):?>
        <div class="alert-box alert")>
          <?php echo $mensajeLogin ?>
          <a href="" class="close">&times;</a>
        </div>
      <?php endif ?>
      <div class="row">  
        <a href="<?php echo site_url("usuarios/recuperarContrasena")?>">¿Olvidó la contraseña?</a>
      </div>
    </fieldset>
  </form>
<?php else: ?>
  <div id="datosUsuario">
    <form id="logout" action="<?php echo site_url("usuarios/logout")?>" method="post">
      <div class="row">
        <div class="twelve mobile-two columns pull-two-mobile"> 
          <h6><?php echo $usuarioLogin->username?></h6>
          <input type="submit" name="submit" value="Cerrar sesión" class="small button" />
        </div>  
      </div>
      <div class="row">  
        <div class="twelve mobile-two columns pull-two-mobile">
          <a href="<?php echo site_url("usuarios/modificar")?>">Editar</a>
        </div>
      </div>
    </form>      
  </div>
<?php endif ?>