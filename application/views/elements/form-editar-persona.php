<h3>Editar persona</h3>
<form class="custom" action="<?php echo $link?>" method="post"> 
    <input type="hidden" name="IdPersona" value="<?php echo $persona['IdPersona']?>"/>
    <div class="twelve columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="Nombre" value="<?php echo $persona['Nombre']?>"/>
      <?php echo form_error('Nombre'); ?>
      
      <label for="campoApellido">Apellido: </label>
      <input id="campoApellido" type="text" name="Apellido" required value="<?php echo $persona['Apellido']?>"/>
      <?php echo form_error('Nombre'); ?>
      
      <label for="campoEmail">Dirección de correo electrónico: </label>
      <input id="campoEmail" type="text" name="Email" required value="<?php echo $persona['Email']?>"/>
      <?php echo form_error('Email'); ?>
      
      <label for="campoUsuario">Nombre de usuario: </label>
      <input id="campoUsuario" type="text" name="Usuario" required value="<?php echo $persona['Usuario']?>"/>
      <?php echo form_error('Usuario'); ?>
      
      <label for="campoContraseña">Contraseña: </label>
      <input id="campoContraseña" type="password" name="Contrasena" required value="<?php echo $persona['Contraseña']?>"/>
      <?php echo form_error('Contrasena'); ?>
      
      <label for="campoContraseña2">Repetir la contraseña: </label>
      <input id="campoContraseña2" type="password" name="Contrasena2" required/>
      <?php echo form_error('Contrasena2'); ?>
    </div>
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
