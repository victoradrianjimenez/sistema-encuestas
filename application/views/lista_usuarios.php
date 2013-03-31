<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Usuarios - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Docentes y Autoridades</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los usuarios registrados en el sistema (Docentes y Autoridades).</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php include 'templates/submenu-usuarios.php'?>
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
              <th>Último acceso</th>
              <th>Estado</th>
              <th>Acciones</th>
            </thead>
            <?php foreach($lista as $item): ?>  
              <tr>
                <td class="apellido"><?php echo $item['usuario']->apellido?></td>
                <td class="nombre"><?php echo $item['usuario']->nombre?></td>
                <td class="ultimo-acceso"><?php echo date('d/m/Y G:i:s',$item['usuario']->last_login)?></td>
                <td class="estado"><?php echo ($item['usuario']->active)?'Activo':'Inactivo'?></td>
                <td>
                  <a class="modificar" href="<?php echo site_url('usuarios/modificar/'.$item['usuario']->id)?>">Modificar</a> /
                  <a class="eliminar" href="#" value="<?php echo $item['usuario']->id?>">Eliminar</a>
                </td>
              </tr>
            <?php endforeach ?>
          </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('usuarios/nuevo')?>">Agregar usuario</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
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
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
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