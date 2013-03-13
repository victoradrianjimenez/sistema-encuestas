<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Encuestas - <?php echo NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Encuestas</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-encuestas.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Encuestas</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron encuestas.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Año</th>
                <th>Fecha inicio</th>
                <th>Fecha cierre</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="anio"><?php echo $item->año.' / '.PERIODO.' '.$item->cuatrimestre?></td>
                  <td><?php echo date('d/m/Y G:i:s', strtotime($item->fechaInicio))?></td>
                  <td><?php echo date('d/m/Y G:i:s', strtotime($item->fechaFin))?></td>
                  <td>
                    <a class="finalizar" href="#modalFinalizar" role="button" data-toggle="modal" value="<?php echo $item->idEncuesta.'_'.$item->idFormulario?>" title="Cerrar período de encuesta">Finalizar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('encuestas/nueva')?>">Nueva encuesta</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para finalizar un periodo de encuestas -->
  <div id="modalFinalizar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Finalizar período de encuesta</h3>
    </div>
    <form action="<?php echo site_url('encuestas/finalizar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idEncuesta" value="" />
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script>
    $('.finalizar').click(function(){
      id = $(this).attr('value').split("_");
      idEncuesta = id[0];
      idFormulario = id[1];
      nombre = $(this).parentsUntil('tr').parent().find('.anio').text();
      $('#modalFinalizar input[name="idEncuesta"]').val(idEncuesta);
      $('#modalFinalizar input[name="idFormulario"]').val(idFormulario);
      //pongo el nombre del departamento en el dialogo
      $("#modalFinalizar").find('.nombre').html(nombre);
      $("#modalFinalizar").modal();
      return false;
    });
  
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>