<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Materias - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
            <?php include 'templates/descripcion-departamentos.php'?>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 3;
            include 'templates/submenu-facultad.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Materias</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Nombre</th>
                <th>Código</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="nombre" href="<?php echo site_url("materias/ver/".$item->idMateria)?>"><?php echo $item->nombre?></a></td>
                  <td><?php echo $item->codigo?></td>
                  <td>
                    <a class="modificar" href="<?php echo site_url('materias/modificar/'.$item->idMateria)?>">Modificar</a> /
                    <a class="eliminar" href="#" value="<?php echo $item->idMateria?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('materias/nueva')?>">Agregar materia</a>
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
      <h3 id="myModalLabel">Eliminar materia</h3>
    </div>
    <form action="<?php echo site_url('materias/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idMateria" value="" />
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <script>
    $('.eliminar').click(function(){
      idMateria = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id de la materia en el formulario
      $('#modalEliminar input[name="idMateria"]').val(idMateria);
      //pongo el nombre de la materia en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre);
      $("#modalEliminar").modal();
      return false;
    });
  </script>
</body>
</html>