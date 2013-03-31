<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Departamentos - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
          <h3>Gestión de Departamentos, Carreras y Materias</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los departamentos pertenecientes a la facultad. Las funcionalidades disponibles permiten agregar nuevos departamentos, modificar o bien eliminar departamentos existentes.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-facultad.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Departamentos</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron departamentos.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Nombre</th>
                <th>Jefe de Departamento</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="nombre"><?php echo $item['departamento']->nombre?></td>
                  <td><?php echo $item['jefeDepartamento']->nombre.' '.$item['jefeDepartamento']->apellido?></td>
                  <td>
                    <a class="modificar" href="<?php echo site_url('departamentos/modificar/'.$item['departamento']->idDepartamento)?>">Modificar</a> / 
                    <a class="eliminar" href="#modalEliminar" role="button" data-toggle="modal" value="<?php echo $item['departamento']->idDepartamento?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
          
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('departamentos/nuevo')?>">Agregar departamento</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para eliminar materias -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar departamento</h3>
    </div>
    <form action="<?php echo site_url('departamentos/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idDepartamento" value="" />
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
      idDepartamento = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id del departamento en el formulario
      $('#modalEliminar input[name="idDepartamento"]').val(idDepartamento);
      //pongo el nombre del departamento en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre);
      $("#modalEliminar").modal();
      return false;
    });
  </script>
</body>
</html>