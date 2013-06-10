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

  <title>Ranking de docentes por Carrera - <?php echo NOMBRE_SISTEMA?></title>
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
        <h4 class="text-center"><?php echo $carrera->nombre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5>Ranking de Docentes</h5>
        <table class="table table-condensed">
          <thead>
            <tr>
              <?php 
                echo '<th>Pos.</th>';
                echo '<th>Docente</th>';
                echo '<th>Asignatura</th>';
                echo '<th>Encuestas contestadas</th>';
                echo '<th>Indice</th>';
              ?>
            </tr>
          </thead>
          <tbody>
            <?php 
              $pos = 1;
              foreach ($indices as $key => $indice){
                $dm = $datos_docentes[$key]; 
                echo '<tr>';
                echo '<td>'.$pos++.'</td>';
                echo '<td>'.$dm['docente']->nombre.' '.$dm['docente']->apellido.'</td>';
                echo '<td>'.$dm['materia']->nombre.' ('.$dm['materia']->codigo.')</td>';
                echo '<td>'.$dm['cantidad'].'</td>'; 
                echo '<td><b>'.(($indice)?round($indice,2):$indice).'</b></td>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
        <p>En el listado se muestra el indice promedio obtenido por cada docente. La última columna corresponde al indice obtenido, medido en una escala de 0 a 10.</p>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>

  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
</body>
</html>