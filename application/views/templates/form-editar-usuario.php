<!-- Última revisión: 2012-02-01 6:04 p.m. -->

<input type="hidden" name="id" value="<?php echo $usuario->id?>"/>
<div class="control-group">
  <label class="control-label"  for="campoNombre">Nombre: </label>
  <div class="controls">
    <input id="campoNombre" type="text" name="nombre" value="<?php echo $usuario->nombre?>"/>
    <?php echo form_error('nombre'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label"  for="campoApellido">Apellido: </label>
  <div class="controls">
    <input id="campoApellido" type="text" name="apellido" required value="<?php echo $usuario->apellido?>"/>
    <?php echo form_error('apellido'); ?>
  </div>
</div>
<div class="control-group">  
  <label class="control-label" for="campoEmail">Dirección de correo electrónico: </label>
  <div class="controls">
    <input id="campoEmail" type="text" name="email" required value="<?php echo $usuario->email?>"/>
    <?php echo form_error('email'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoUsuario">Nombre de usuario: </label>
  <div class="controls">
    <input id="campoUsuario" type="text" name="username" required value="<?php echo $usuario->username?>"/>
    <?php echo form_error('username'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoContraseña">Contraseña: </label>
  <div class="controls">
    <input id="campoContraseña" type="password" name="password" value=""/>
    <?php echo form_error('password'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoContraseña2">Confirmar contraseña: </label>
  <div class="controls">
    <input id="campoContraseña2" type="password" name="password2" value=""/>
    <?php echo form_error('password2'); ?>
  </div>
</div>
<div class="control-group">
  <div class="twelve columns">
    <label>Grupos: </label>
  </div>
  <?php foreach($grupos as $grupo){
    $selected = '';
    //verifico si el el usuario pertenece al grupo actual
    foreach ($usuario_grupos as $g) {
      if ($grupo->id == $g->id){
        $selected = 'checked';
        break;
      }
    }
    echo '
      <div class="six mobile-two columns end">
        <input type="checkbox" name="grupo_'.$grupo->id.'" '.$selected.'/> '.$grupo->description.'
      </div>';
  }?>
</div>
<script>
  $('input[type="text"], input[type="password"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>