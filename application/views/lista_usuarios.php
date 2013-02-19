<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Usuarios</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <?php include 'templates/menu-nav.php'?>
  <div id="wrapper" class="container">
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
          <li class="<?php if (!isset($grupo))echo 'active'?>"><a href="<?php echo site_url("usuarios")?>" href="#">Todos los usuarios</a></li>
          <li class="<?php if (isset($grupo))echo($grupo->name=="decanos")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDecanos")?>">Decano</a></li>
          <li class="<?php if (isset($grupo))echo($grupo->name=="jefes_departamentos")?'active':''?>"><a href="<?php echo site_url("usuarios/listarJefesDepartamentos")?>">Jefes de departamento</a></li>
          <li class="<?php if (isset($grupo))echo($grupo->name=="directores")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDirectores")?>">Directores de carrera</a></li>
          <li class="<?php if (isset($grupo))echo($grupo->name=="docentes")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDocentes")?>">Docentes</a></li>
        </ul>
      </div>

      <!-- Main -->
      <div class="span9">
      <h4><?php echo(isset($grupo))?'Usuarios del grupo '.$grupo->description:'Todos los usuarios'?></h4>
      <?php if(count($lista)== 0):?>
        <p>No se encontraron usuarios.</p>
      <?php else:?>
        <table class="table table-bordered table-striped">
          <thead>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
          </thead>
          <?php foreach($lista as $item): ?>  
            <tr>
              <td><a class="apellido" href="<?php echo site_url("usuarios/ver/".$item['usuario']->id)?>"><?php echo $item['usuario']->apellido?></a></td>
              <td class="nombre"><?php echo $item['usuario']->nombre?></td>
              <td class="email"><?php echo $item['usuario']->email?></td>
              <td>
                <a class="eliminar" href="#" value="<?php echo $item['usuario']->id?>">Eliminar</a>
              </td>
            </tr>
          <?php endforeach ?>
        </table>
        <?php endif ?>
        <?php echo $paginacion ?>

        <!-- Botones -->
        <div class="btn-group">
          <button class="btn btn-primary" href="#modalAgregar" role="button" data-toggle="modal">Agregar usuario...</button>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para agregar una usuario -->
  <div id="modalAgregar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Crear nuevo usuario</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('usuarios/nuevo')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-editar-usuario.php'?>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- ventana modal para eliminar usuarios --> 
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar usuario</h3>
    </div>
    <form action="<?php echo site_url('usuarios/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="id" value="" />
        <h5 class="nombre"></h5>
        <p>¿Desea continuar?</p>      
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
    $('.eliminar').click(function(){
      id = $(this).attr('value');
      apellido = $(this).parentsUntil('tr').parent().find('.apellido').text();
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id de la usuario en el formulario
      $('#modalEliminar input[name="id"]').val(id);
      //pongo el nombre de la usuario en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre+' '+apellido);
      $("#modalEliminar").modal();
      return false;
    });
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>