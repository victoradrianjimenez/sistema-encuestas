<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <style>
    img.foto{width:150px; height:150px;}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
          <h3>Gestión de Docentes y Autoridades</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los usuarios registrados en el sistema (Docentes y Autoridades).</p>
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
                <h4><?php echo $tituloFormulario?></h4>
                <img class="foto" src="<?php echo site_url('usuarios/imagen/'.$usuario->idImagen)?>" width="150" height="150" alt="Imagen de usuario"/>               
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoNombre">Nombre: </label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" maxlength="40" value="<?php echo $usuario->nombre?>"/>
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"  for="campoApellido">Apellido: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoApellido" type="text" name="apellido" maxlength="40" required value="<?php echo $usuario->apellido?>"/>
                <?php echo form_error('apellido'); ?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="campoEmail">E-mail: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoEmail" type="text" name="email" maxlength="100" required value="<?php echo $usuario->email?>"/>
                <?php echo form_error('email'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoUsuario">Nombre de usuario: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoUsuario" type="text" name="username" maxlength="100" required value="<?php echo $usuario->username?>"/>
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
                <input id="campoImagen" type="file" name="imagen"/>
                <?php echo form_error('imagen'); ?>
                <label class="checkbox"><input type="checkbox" name="noImagen" value="1" <?php echo ($noImagen)?'checked="checked"':''?> />Eliminar imagen actual</label>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Estado: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="active" value="1" <?php echo ($usuario->active)?'checked="checked"':''?> />Cuenta de usuario Activa</label>
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
                          $selected = 'checked="checked"';
                          break;
                        }
                      }
                      
                      if(isset($_POST['grupos'])){
                        foreach ($_POST['grupos'] as $g) {
                          if ($grupo->id == $g){
                            $selected = 'checked="checked"';
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
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
</body>
</html>  
