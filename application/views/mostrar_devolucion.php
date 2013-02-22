<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/bootstrap-responsive.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.css')?>" rel="stylesheet">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>
  
  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="ico/favicon.png">

  <title>Devolución</title>
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{text-align:center;}
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
    .row-fluid [class*="span"]{margin-left:0;}
  </style>
</head>
<body>
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2>Evaluación de la cátedra sobre los resultados de las encuestas</h2>
        <h4>Asignatura: <?php echo $materia->nombre.' ('.$materia->codigo.')'?></h4>
        <h4>Cuatrimestre / período: <?php echo $encuesta->año.' / '.$encuesta->cuatrimestre?></h4>
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
</body>
</html>