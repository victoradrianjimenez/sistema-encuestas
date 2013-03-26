<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.css')?>" rel="stylesheet">
  <style>body{padding-top:40px;}</style>
  <link href="<?php echo base_url('css/bootstrap-responsive.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/imprimir.css')?>" rel="stylesheet" media="print">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>

  <title>Plan de Mejoras - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
    .row-fluid [class*="span"]{margin-left:0;}
  </style>
</head>
<body>
  <!-- Menu de opciones -->
  <div id="barra-herramientas">
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="<?php echo site_url()?>">Sistema Encuestas</a>
          <ul class="nav">
            <li><a href="#" onclick="window.print()">Imprimir...</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2 class="text-center">Evaluación de la cátedra sobre los resultados de las encuestas</h2>
        <h4 class="text-center">Asignatura: <?php echo $materia->nombre.' ('.$materia->codigo.')'?></h4>
        <h4 class="text-center">Cuatrimestre/<?php echo PERIODO?>: <?php echo $encuesta->año.' / '.$encuesta->cuatrimestre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5 class="separador">Fortalezas del curso</h5>
        <p><?php echo $devolucion->fortalezas ?></p>
        <h5 class="separador">Debilidades del curso</h5>
        <p><?php echo $devolucion->debilidades ?></p>
        <h5 class="separador">Reflexiones sobre las opiniones de los alumnos</h5>
        <p><?php echo $devolucion->alumnos ?></p>
        <h5 class="separador">Reflexiones sobre el desempeño de los docentes</h5>
        <p><?php echo $devolucion->docentes ?></p>
        <h5 class="separador">Plan de mejoras propuesto</h5>
        <p><?php echo $devolucion->mejoras ?></p>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>
  
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
</body>
</html>