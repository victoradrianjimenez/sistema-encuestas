<?php if(!(isset($usuarioLogin) && is_a($usuarioLogin, 'Persona'))): ?>
  <form id="login" action="<?php echo site_url("personas/login")?>" method="post"> 
    <fieldset>
      <legend>Iniciar sesión</legend>
      <div class="row">
        <label for="loginUsuario">Usuario</label>
        <input id="loginUsuario" type="text" name="usuario" required />
        <label for="loginContraseña">Contraseña</label>
        <input id="loginContraseña" type="password" name="contrasena" required />
      </div>
      <div class="row">
        <input type="checkbox" name="recordarme" />Recordarme              
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
        <a href="<?php echo site_url("personas/resetearContraseña")?>">¿Olvidó la contraseña?</a>
      </div>
    </fieldset>
  </form>
<?php else: ?>
  <div id="datosUsuario">
    <form id="logout" action="<?php echo site_url("personas/logout")?>" method="post"> 
      <h6><?php echo $usuarioLogin->Nombre.' '.$usuarioLogin->Apellido?></h6>
      <input type="submit" name="submit" value="Cerrar sesión" class="small button" />
    </form>      
  </div>
<?php endif ?>