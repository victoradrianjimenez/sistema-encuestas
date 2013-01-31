<h3><?php echo $titulo?></h3>
<form class="custom" action="<?php echo $link?>" method="post"> 
    <input type="hidden" name="id" value="<?php echo $usuario['id']?>"/>
    <div class="twelve columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="nombre" value="<?php echo $usuario['nombre']?>"/>
      <?php echo form_error('nombre'); ?>
      
      <label for="campoApellido">Apellido: </label>
      <input id="campoApellido" type="text" name="apellido" required value="<?php echo $usuario['apellido']?>"/>
      <?php echo form_error('apellido'); ?>
      
      <label for="campoEmail">Dirección de correo electrónico: </label>
      <input id="campoEmail" type="text" name="email" required value="<?php echo $usuario['email']?>"/>
      <?php echo form_error('email'); ?>
      
      <label for="campoUsuario">Nombre de usuario: </label>
      <input id="campoUsuario" type="text" name="username" required value="<?php echo $usuario['username']?>"/>
      <?php echo form_error('username'); ?>
      
      <label for="campoContraseña">Contraseña: </label>
      <input id="campoContraseña" type="password" name="password" value=""/>
      <?php echo form_error('password'); ?>
      
      <label for="campoContraseña2">Confirmar contraseña: </label>
      <input id="campoContraseña2" type="password" name="password2" value=""/>
      <?php echo form_error('password2'); ?>
    </div>
    <div class="row">   
      <div class="twelve columns">
        <label>Grupos: </label>
      </div>
      <?php foreach($grupos as $grupo){
        $selected = '';
        //verifico si el el usuario pertenece al grupo actual
        foreach ($usuario['grupos'] as $g) {
          if ($grupo['id'] == $g['id'] ){
            $selected = 'checked';
          }
        }           
        echo '
          <div class="six mobile-two columns end">
            <input type="checkbox" name="grupo_'.$grupo['id'].'" '.$selected.'/> '.$grupo['description'].'
          </div>';
      }?>
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
