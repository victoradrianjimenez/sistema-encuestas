<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Carreras - <?php echo NOMBRE_SISTEMA?></title>
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
          <?php $item_submenu = 2;
            include 'templates/submenu-facultad.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Carreras</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron carreras.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Nombre</th>
                <th>Plan</th>
                <th>Director</th>
                <th>Departamento</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="nombre" href="<?php echo site_url("carreras/ver/".$item['carrera']->idCarrera)?>"><?php echo $item['carrera']->nombre?></a></td>
                  <td class="plan"><?php echo $item['carrera']->plan?></td>
                  <td class="director"><?php echo $item['director']->nombre.' '.$item['director']->apellido?></td>
                  <td class="departamento"><?php echo $item['departamento']->nombre?></td>
                  <td>
                    <a class="modificar" href="<?php echo site_url('carreras/modificar/'.$item['carrera']->idCarrera)?>">Modificar</a> /
                    <a class="eliminar" href="#" value="<?php echo $item['carrera']->idCarrera?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
          
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('carreras/nueva')?>">Agregar carrera</a>
          </div>      
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
 
  <!-- ventana modal para eliminar una carrera -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar carrera</h3>
    </div>
    <form action="<?php echo site_url('carreras/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idCarrera" value="" />
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
      idCarrera = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      plan = $(this).parentsUntil('tr').parent().find('.plan').text();
      $('#modalEliminar input[name="idCarrera"]').val(idCarrera);
      //pongo el nombre del departamento en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre + ' - Plan: '+plan);
      $("#modalEliminar").modal();
      return false;
    });
  </script>
</body>
</html>