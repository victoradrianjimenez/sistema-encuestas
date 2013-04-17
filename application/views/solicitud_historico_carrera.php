<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar histórico por Carrera - <?php echo NOMBRE_SISTEMA?></title>
  <link href="<?php echo base_url('css/datepicker.min.css')?>" rel="stylesheet">
  <style>
    #contenedor .form-horizontal .controls {margin-left: 100px}
    #contenedor .form-horizontal .control-label {width: 80px; float: left}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Informes Históricos</h3>
          <p>En esta sección se permite acceder a un informe que contiene un resumen de las respuestas para una pregunta en más de un período, para una carrera en particular.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 2;
            include 'templates/submenu-historicos.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
        <h4>Solicitar informe por carrera</h4>
          <form class="form-horizontal" action="<?php echo site_url('historicos/carrera')?>" method="post">
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarCarrera" name="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarCarrera')?>" required><i class="icon-search"></i>
                <input type="hidden" name="idCarrera" value="<?php echo set_value('idCarrera')?>" required/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarPregunta">Pregunta:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarPregunta" name="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarPregunta')?>" required /><i class="icon-search"></i>
                <input type="hidden" name="idPregunta" value="<?php echo set_value('idPregunta')?>"required/>
                <?php echo form_error('idPregunta')?>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span6 control-group">
                <label class="control-label" for="dpd1">Fecha Inicio:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" name="fechaInicio" value="" id="dpd1" data-date-viewmode="months" value="<?php echo set_value('fechaInicio')?>">
                </div>
              </div>
              <div class="span6 control-group">
                <label class="control-label" for="dpd2">Fecha Fin:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" name="fechaFin" value="" id="dpd2" data-date-viewmode="months" value="<?php echo set_value('fechaFin')?>">
                </div>
              </div>
            </div>
            <div class="controls btn-group">
              <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
            </div>
          </form>
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
  <script src="<?php echo base_url('js/bootstrap-typeahead.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-datepicker.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script src="<?php echo base_url('js/fechas.min.js')?>"></script>
  <script>
    autocompletar_carrera($('#buscarCarrera'), "<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_pregunta($('#buscarPregunta'), "<?php echo site_url('preguntas/buscarAjax')?>");
    selectores_fecha($('#dpd1'), $('#dpd2'));
  </script>
</body>
</html>