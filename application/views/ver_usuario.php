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
          <h3><?php echo $usuario->nombre.' '.$usuario->apellido?></h3>
          <h5>Email: <?php echo $usuario->email?></h5>
          <h5>Último acceso: <?php echo date('d/m/Y g:i:s a', $usuario->last_login)?></h5>
          <h5>Estado: <?php echo ($usuario->active)?'Activo':'Inactivo'?></h5>
  
          <!-- Botones -->
          <div class="">
            <button class="btn btn-primary" href="#modalModificar" role="button" data-toggle="modal">Modificar usuario...</button>
            <button class="btn btn-primary" href="#modalActivar" role="button" data-toggle="modal">Activar/Desactivar cuenta</button>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
    
  <!-- ventana modal para editar datos del usuario -->
  <div id="modalModificar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Editar usuario</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('usuarios/modificar')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-editar-usuario.php'?>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- ventana modal para activar/desactivar una cuenta de usuario -->
  <div id="modalActivar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel"><?php echo ($usuario->active)?'Desactivar cuenta de usuario':'Activar cuenta de usuario'?></h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url(($usuario->active)?'usuarios/desactivar':'usuarios/activar')?>" method="post">
      <div class="modal-body">
        <h5 class="nombre"><?php echo $usuario->nombre.' '.$usuario->apellido.' - '.$usuario->email?></h5>
        <p>¿Desea continuar?</p>
        <input type="hidden" name="id" value="<?php echo $usuario->id?>" />  
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script>   
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>