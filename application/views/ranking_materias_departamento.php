<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.min.css')?>" rel="stylesheet">
  <style>body{padding-top:40px;}</style>
  <link href="<?php echo base_url('css/bootstrap-responsive.min.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.min.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/imprimir.css')?>" rel="stylesheet" media="print">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>

  <title>Ranking de materias por Departamento - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <!-- Menu de opciones -->
  <div id="barra-herramientas">
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="<?php echo site_url()?>">Sistema Encuestas</a>
          <ul class="nav">
            <li><a href="#" onclick="window.print()"><i class="icon-print"></i> Imprimir...</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Informe -->
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2 class="text-center"><?php echo NOMBRE_FACULTAD?></h2>
        <h4 class="text-center"><?php echo $departamento->nombre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5>Ranking de Materias</h5>
        <table class="table table-condensed">
          <thead>
            <tr>
              <?php 
                echo '<th>Pos.</th>';
                echo '<th>Carrera</th>';
                echo '<th>Asignatura</th>';
                echo '<th>Encuestas contestadas</th>';
                foreach ($secciones as $seccion){
                  if ($seccion->tipo != SECCION_TIPO_ALUMNO){
                    echo '<th>'.$seccion->texto.'</th>';
                  }
                }
                echo '<th>Indice Global</th>';
              ?>
            </tr>
          </thead>
          <tbody>
            <?php 
              $pos = 1;
              foreach ($indicesGLobales as $key => $indiceGLobal){ 
                foreach ($datos_materias as $d){
                  if ($d['materia']->idMateria.'_'.$d['carrera']->idCarrera == $key){
                    $dm = $d;
                    break;
                  }
                }
                echo '<tr>';
                echo '<td>'.$pos++.'</td>';
                echo '<td>'.$dm['carrera']->nombre.'</td>';
                echo '<td>'.$dm['materia']->nombre.' ('.$dm['materia']->codigo.')</td>';
                echo '<td>'.$dm['cantidad'].'</td>'; 
                foreach ($dm['indices'] as $col){
                  echo '<td>'.(($col)?round($col,2):$col).'</td>';
                }
                echo '<td><b>'.(($indiceGLobal)?round($indiceGLobal,2):$indiceGLobal).'</b></td>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
        <p>En el listado se muestra el indice promedio obtenido por cada materia. La última columna corresponde al indice global, calculado en base a todas las preguntas del formulario. En todos los casos, el índice se mide en una escala de 0 a 10.</p>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>

  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
</body>
</html>