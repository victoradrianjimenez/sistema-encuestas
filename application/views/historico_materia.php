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

  <title>Histórico por Materia - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    h5.separador{border-bottom: 3px solid #2BA6CB;}
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
  <!-- Informe -->
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2 class="text-center"><?php echo $departamento->nombre?></h2>
        <h4 class="text-center"><?php echo $carrera->nombre?></h4>
        <h4 class="text-center">Asignatura: <?php echo $materia->nombre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5>Pregunta:</h5>
        <p><?php echo $pregunta->texto?></p>
        <?php if ($pregunta->tipo == TIPO_NUMERICA):?>
          <ul>
            <li>Valor Máximo: <?php echo $pregunta->limiteInferior?>
            <li>Valor Mínimo: <?php echo $pregunta->limiteSuperior?>
            <li>Paso: <?php echo $pregunta->paso?>
          </ul>          
        <?php else:?>
          <ul>
            <?php foreach ($opciones as $opcion){
              echo "<li>$opcion->idOpcion = $opcion->texto</li>";
            }?>
          </ul>
        <?php endif?>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5>Respuestas:</h5>
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>Año / <?php echo PERIODO?></th>
              <th>Respuesta Promedio</th>
              <th>Desviación estándar</th>
              <th>Encuestas Contestadas</th>
              <th>Total de Encuestados</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($datos as $fila):?>
            <tr>
              <td><?php echo $fila['año'].'/'.$fila['cuatrimestre']?></td>
              <td><?php echo $fila['promedio']?></td>
              <td><?php echo $fila['std']?></td>
              <td><?php echo $fila['contestadas']?></td>
              <td><?php echo $fila['cantidad']?></td>
            </tr>
            <?php endforeach?>
          </tbody>
        </table>
        <h5>Gráfico:</h5>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <img src="<?php echo site_url('pCharts/graficoHistoricoMateria/'.
          $materia->idMateria.'/'.$carrera->idCarrera."/".$pregunta->idPregunta.'/'.$fechaInicio.'/'.$fechaFin)
          ?>" width="600" height="200" style="margin:0 auto" />      
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>

  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
</body>
</html>