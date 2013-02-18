<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Encuestas</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <?php include 'templates/menu-nav.php'?>
  <div id="wrapper" class="container">
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
        <h4>Navegación</h4>
        <ul class="nav nav-pills nav-stacked">      
          <li class="active"><a href="<?php echo site_url("encuestas")?>">Encuestas realizadas</a></li>
          <li><a href="<?php echo site_url("claves")?>">Claves de acceso</a></li>
        </ul>
      </div>
      
      <!-- Main -->
      <div class="span9">
        <h4>Encuestas</h4>
        <?php if(count($lista)== 0):?>
          <p>No se encontraron encuestas.</p>
        <?php else:?>
          <table class="table table-bordered table-striped">
            <thead>
              <th>Año / Periodo</th>
              <th>Fecha inicio</th>
              <th>Fecha cierre</th>
              <th>Acciones</th>
            </thead>
            <?php foreach($lista as $item): ?>  
              <tr>
                <td><a href="<?php echo site_url("encuestas/ver/".$item->idEncuesta.'/'.$item->idFormulario)?>">
                  <?php echo $item->año.' / '.$item->cuatrimestre?>
                </a></td>
                <td><?php echo $item->fechaInicio?></td>
                <td><?php echo $item->fechaFin?></td>
                <td>
                </td>
              </tr>
            <?php endforeach ?>
          </table>
        <?php endif ?>
        <?php echo $paginacion ?>

        <!-- Botones -->
        <div class="btn-group">
          <button class="btn btn-primary" href="#modalAgregar" role="button" data-toggle="modal">Nueva Encuesta...</button>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para agregar una encuesta -->
  <div id="modalAgregar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Crear nueva Encuesta</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('encuestas/nueva')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-editar-encuesta.php'?>      
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