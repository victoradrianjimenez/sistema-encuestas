<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Devoluciones</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Devoluciones</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- Main -->
        <div class="span12">
          <h4>Devoluciones</h4>
          <p>Asignatura: <?php echo $materia->nombre.' / '.$materia->codigo?></p>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron devoluciones.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Fecha</th>
                <th>Encuesta</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="fecha" href="<?php echo site_url('devoluciones/ver/'.$item['devolucion']->idDevolucion.'/'.$item['devolucion']->idMateria.'/'.$item['devolucion']->idEncuesta.'/'.$item['devolucion']->idFormulario)?>"/>
                    <?php echo $item['devolucion']->fecha?>
                  </a></td>
                  <td><?php echo $item['encuesta']->año.' / '.$item['encuesta']->cuatrimestre?></td>
                  <td><a class="eliminar" href="#" value="<?php echo $item['devolucion']->idDevolucion?>">Eliminar</a></td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <form action="<?php echo site_url('devoluciones/nueva')?>" method="post">
              <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
              <input type="submit" name="submit" class="btn btn-primary" value="Agregar devolucion" />
            </form>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para eliminar devoluciones -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar devolución</h3>
    </div>
    <form action="<?php echo site_url('devoluciones/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idDevolucion" value="" />
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
      idDevolucion = $(this).attr('value');
      fecha = $(this).parentsUntil('tr').parent().find('.fecha').text();
      //cargo el id de la devolucion en el formulario
      $('#modalEliminar input[name="idDevolucion"]').val(idDevolucion);
      //pongo la fecha de la devolucion en el dialogo
      $("#modalEliminar").find('.nombre').html(fecha);
      $("#modalEliminar").modal();
      return false;
    });
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>