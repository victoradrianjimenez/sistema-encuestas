<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Formularios</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <?php include 'templates/menu-nav.php'?>
  <div id="wrapper" class="container">
    <div class="row">
      <!-- Titulo -->
      <div class="span12">
        <h3>Gestión de Formularios</h3>
        <p>---Descripción---</p>
      </div>
    </div>
    
    <div class="row">
      <!-- SideBar -->
      <div class="span3" id="menu">
        <h4>Navegación</h4>
        <ul class="nav nav-pills nav-stacked">      
          <li class="active"><a href="<?php echo site_url("formularios")?>">Formularios</a></li>
          <li><a href="<?php echo site_url("preguntas")?>">Preguntas</a></li>
        </ul>
      </div>
      
      <!-- Main -->
      <div class="span9">
        <h4>Formularios</h4>
        <?php if(count($lista)== 0):?>
          <p>No se encontraron formularios.</p>
        <?php else:?>
          <table class="table table-bordered table-striped">
            <thead>
              <th>Nombre</th>
              <th>Título</th>
              <th>Creacion</th>
              <th>Acciones</th>
            </thead>
            <?php foreach($lista as $item): ?>  
              <tr>
                <td><a class="nombre" href="<?php echo site_url('formularios/ver/'.$item->idFormulario)?>"/><?php echo $item->nombre?></a></td>
                <td class="titulo"><?php echo $item->titulo?></td>
                <td class="creacion"><?php echo $item->creacion?></td>
                <td>
                  <a class="eliminar" href="#" value="<?php echo $item->idFormulario?>">Eliminar</a>
                </td>
              </tr>
            <?php endforeach ?>
          </table>
        <?php endif ?>
        <?php echo $paginacion ?>

        <!-- Botones -->
        <div class="btn-group">
          <a class="btn btn-primary" href="<?php echo site_url('formularios/editar')?>">Agregar formulario</a>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para eliminar formularios -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar formulario</h3>
    </div>
    <form action="<?php echo site_url('formularios/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idFormulario" value="" />
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
      idFormulario = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id del departamento en el formulario
      $('#modalEliminar input[name="idFormulario"]').val(idFormulario);
      //pongo el nombre del departamento en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre);
      $("#modalEliminar").modal();
      return false;
    });
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>