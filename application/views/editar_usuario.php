<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ver usuario</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Docentes y Autoridades</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <h4>Navegación</h4>
          <ul class="nav nav-pills nav-stacked">      
            <li class="active"><a href="<?php echo site_url("usuarios")?>">Todos los usuarios</a></li>
            <li><a href="<?php echo site_url("usuarios/listarDecanos")?>">Decano</a></li>
            <li><a href="<?php echo site_url("usuarios/listarJefesDepartamentos")?>">Jefes de departamento</a></li>
            <li><a href="<?php echo site_url("usuarios/listarDirectores")?>">Directores de carrera</a></li>
            <li><a href="<?php echo site_url("usuarios/listarDocentes")?>">Docentes</a></li>
          </ul>
        </div>
        
        <!-- Main -->
        <div class="span9">
          
          <form class="form-horizontal" action="<?php echo site_url('usuarios/modificar')?>" method="post">
            <input type="hidden" name="id" value="<?php echo $usuario->id?>"/>
            
            <div class="control-group">
              <div class="controls">
                <h4>Editar Usuario</h4>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoNombre">Nombre: </label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" value="<?php echo $usuario->nombre?>"/>
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoApellido">Apellido: </label>
              <div class="controls">
                <input class="input-block-level" id="campoApellido" type="text" name="apellido" required value="<?php echo $usuario->apellido?>"/>
                <?php echo form_error('apellido'); ?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="campoEmail">E-mail: </label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="text" name="email" required value="<?php echo $usuario->email?>"/>
                <?php echo form_error('email'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoUsuario">Nombre de usuario: </label>
              <div class="controls">
                <input class="input-block-level" id="campoUsuario" type="text" name="username" required value="<?php echo $usuario->username?>"/>
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
              <label class="control-label">Grupos: </label>
              <div class="controls">
                <div class="row-fluid">
                  <?php
                    $col = 0; 
                    foreach($grupos as $grupo){
                      $selected = '';
                      //verifico si el el usuario pertenece al grupo actual
                      foreach ($usuario_grupos as $g) {
                        if ($grupo->id == $g->id){
                          $selected = 'checked';
                          break;
                        }
                      }
                      echo '
                      <div class="span6">
                        <input type="checkbox" name="grupo_'.$grupo->id.'" '.$selected.'/> '.$grupo->description.'
                      </div>';
                      if ($col==1){
                        echo '</div><div class="row-fluid">';
                      }
                      $col = ($col + 1)%2; 
                    }?>
                  </div>
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
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script>
    $('input[type="text"], input[type="password"]').keyup(function(){
      $(this).siblings('span.label').hide('fast');
    });
  </script>
</body>
</html>  
