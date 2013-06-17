<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Devoluciones</title>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Planes de mejora</h3>
          <p>En esta sección se permite acceder a un informe de plan de mejoras creados hasta el momento por una materia de una carrera en particular.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- Main -->
        <div class="span12">
          <h4>Planes de Mejoras</h4>
          <p>Carrera: <?php echo $carrera->nombre?></p>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron planes de mejoras.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Fecha</th>
                <th>Encuesta</th>
                <th>Materia</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="fecha"><?php echo date('d/m/Y G:i:s', strtotime($item['devolucion']->fecha))?></td>
                  <td><?php echo $item['encuesta']->año.' / '.$item['encuesta']->cuatrimestre?></td>
                  <td><?php echo $item['materia']->nombre.' ('.$item['materia']->codigo.')'?></td>
                  <td>
                    <a href="<?php echo site_url('devoluciones/ver/'.$item['devolucion']->idMateria.'/'.$item['devolucion']->idEncuesta.'/'.$item['devolucion']->idFormulario)?>"/>Ver</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
</body>
</html>