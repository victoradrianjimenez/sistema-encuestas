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
  <link href="<?php echo base_url('css/imprimir-claves.css')?>" rel="stylesheet" media="print">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>

  <title>Claves de acceso - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    .clave{border-bottom: 1px dashed #000000; page-break-inside:avoid;}
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
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Descargar <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="form"><form action="<?php echo site_url('claves/listar')?>" method="post">
                    <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
                    <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
                    <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
                    <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
                    <input type="hidden" name="imprimirPDF" value="1" />
                    <input type="submit" name="submit" class="btn btn-link" value="Como PDF..." />
                  </form></a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Informe -->
  <div class="container">
    <?php foreach ($lista as $clave):?>
      <div class=" clave">
        <div class="row">
          <div class="span12">
            <h3 class="text-center" style="margin:0;">Encuesta para mejorar la calidad de la enseñanza</h3>
            <h5 class="text-center" style="margin:0;"><?php echo $carrera->nombre.' - '.$departamento->nombre?></h5>
          </div>
        </div>
        <div class="row">
          <div class="span12">
            <h4 style="float:left; margin:4px 0";><b><?php echo $materia->nombre?></b></h4>
            <h4 style="float:right; margin:4px 0";"><b><?php echo $clave->clave?></b></h4>
          </div>
        </div>      
        <div class="row">
          <div class="span12">
            <p style="text-align:justify;">Esta clave es necesaria para completar la encuesta desarrollada por la <?php echo NOMBRE_FACULTAD?> - <?php echo NOMBRE_UNIVERSIDAD?>, destinada a mejorar la calidad de la enseñanza. 
            Para utilizarla deberá acceder a la dirección web <?php echo base_url()?>. 
            Esta clave sirve para responder sobre una asignatura en particular y dejará de tener validez al completar el formulario.
            Usted debería solicitar una clave distinta por cada asignatura que esté cursando en forma regular.</p>
          </div>
        </div>
      </div>
    <?php endforeach?>
  </div>
  
  <?php //include 'templates/footer2.php'?>

  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
</body>
</html>