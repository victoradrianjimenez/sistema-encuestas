<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar histórico por Departamento - <?php echo NOMBRE_SISTEMA?></title>
  <link href="<?php echo base_url('css/datepicker.css')?>" rel="stylesheet">
  <style>
    .form-horizontal .controls {margin-left: 100px}
    .form-horizontal .control-label {width: 80px; float: left}
    #contenedor{padding-top:9px}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <?php include 'templates/descripcion-historicos.php'?>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 3;
            include 'templates/submenu-historicos.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Solicitar informe por departamento</h4>
          <form class="form-horizontal" action="<?php echo site_url('historicos/departamento')?>" method="post">
            <div class="control-group">
              <label class="control-label" for="buscarDepartamento">Departamento: </label>
              <div class="controls">
                <input class="input-block-level" id="buscarDepartamento" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idDepartamento" required/>
                <?php echo form_error('idDepartamento')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarPregunta">Pregunta:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idMateria" required/>
                <?php echo form_error('idPregunta')?>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span6 control-group">
                <label class="control-label" for="dpd1">Fecha Inicio:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" value="" id="dpd1" data-date-viewmode="months">
                </div>
              </div>
              <div class="span6 control-group">
                <label class="control-label" for="dpd1">Fecha Fin:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" value="" id="dpd2" data-date-viewmode="months">
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
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-datepicker.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script src="<?php echo base_url('js/fechas.js')?>"></script>
  <script>
    autocompletar_departamento("<?php echo site_url('departamentos/buscarAJAX')?>");
    autocompletar_pregunta("<?php echo site_url('preguntas/buscarAjax')?>");
  </script>
</body>
</html>