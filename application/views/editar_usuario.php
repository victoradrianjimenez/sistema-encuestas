<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar usuario</title>
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
          <form action="<?php echo $urlFormulario?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $usuario->id?>"/>
            
            <div class="control-group">
              <div class="controls">
                <h4>Editar Usuario</h4>
                <img src="<?php echo site_url('usuarios/imagen/'.$usuario->idImagen)?>" width="150" height="150" alt="Imagen de usuario"/>               
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoNombre">Nombre: </label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" value="<?php echo (set_value('nombre'))?set_value('nombre'):$usuario->nombre?>"/>
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoApellido">Apellido: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoApellido" type="text" name="apellido" required value="<?php echo (set_value('apellido'))?set_value('apellido'):$usuario->apellido?>"/>
                <?php echo form_error('apellido'); ?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="campoEmail">E-mail: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="text" name="email" required value="<?php echo (set_value('email'))?set_value('email'):$usuario->email?>"/>
                <?php echo form_error('email'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoUsuario">Nombre de usuario: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoUsuario" type="text" name="username" required value="<?php echo (set_value('username'))?set_value('username'):$usuario->username?>"/>
                <?php echo form_error('username'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoContraseña">Contraseña: </label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña" type="password" name="password" value="<?php echo set_value('password')?>"/>
                <?php echo form_error('password'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoContraseña2">Confirmar contraseña: </label>
              <div class="controls">
                <input class="input-block-level" id="campoContraseña2" type="password" name="password2" value="<?php echo set_value('password2')?>"/>
                <?php echo form_error('password2'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoImagen">Subir una imágen (tamaño máximo 500KB): </label>
              <div class="controls">
                <input id="campoImagen" type="file" name="imagen"/>
                <?php echo form_error('imagen'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Estado: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="active" <?php echo (isset($_POST['active']) || $usuario->active)?'checked="checked"':''?> />Cuenta de usuario Activa</label>
                <?php echo form_error('active')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Grupos: </label>
              <div class="controls">
                <div class="row-fluid">
                  <?php
                    foreach($grupos as $i => $grupo){
                      $selected = '';
                      //verifico si el el usuario pertenece al grupo actual
                      foreach ($usuario_grupos as $g) {
                        if ($grupo->id == $g->id){
                          $selected = 'checked';
                          break;
                        }
                      }
                      if(isset($_POST['grupos'])){
                        foreach ($_POST['grupos'] as $g) {
                          if ($grupo->id == $g){
                            $selected = 'checked';
                            break;
                          }
                        }
                      }
                      echo '
                      <div class="span6">
                        <label class="checkbox"><input type="checkbox" name="grupos[]" value="'.$grupo->id.'" '.$selected.'/> '.$grupo->description.'</label>
                      </div>';
                      echo ($i%2==1)?'</div><div class="row-fluid">':''; 
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
</body>
</html>  
