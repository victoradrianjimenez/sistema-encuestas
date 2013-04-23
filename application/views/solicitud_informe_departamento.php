<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar Informe por Departamento - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    #contenedor .form-horizontal .controls {margin-left: 110px}
    #contenedor .form-horizontal .control-label {width: 90px; float: left}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Informes por Encuesta</h3>
          <p>Esta sección permite acceder a un informe que contiene un resumen de las respuestas obtenidas por un departamento en una determinada encuesta.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 3;
            include 'templates/submenu-informes.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Solicitar informe por departamento</h4>
          <form class="form-horizontal" action="<?php echo site_url('informes/departamento')?>" method="post">
            <div class="control-group">
              <label class="control-label" for="buscarDepartamento">Departamento:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarDepartamento" name="buscarDepartamento" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarDepartamento')?>" required><i class="icon-search"></i>
                <input type="hidden" name="idDepartamento" value="<?php echo set_value('idDepartamento')?>" required/>
                <?php echo form_error('idDepartamento')?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="buscarEncuesta">Año:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarEncuesta" name="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarEncuesta')?>" required><i class="icon-search"></i>
                <input type="hidden" name="idEncuesta" value="<?php echo set_value('idEncuesta')?>" required/>
                <?php echo form_error('idEncuesta')?>
                <input type="hidden" name="idFormulario" value="<?php echo set_value('idFormulario')?>" required/>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="graficos" value="1" <?php echo set_checkbox('graficos', '1', TRUE)?> />Incluir gráficos de barras</label>
                <label class="checkbox"><input type="checkbox" name="respuestaPromedio" value="1" <?php echo set_checkbox('respuestaPromedio', '1', TRUE)?> />Incluir respuesta promedio para cada pregunta</label>
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
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script>
    autocompletar_departamento($('#buscarDepartamento'), "<?php echo site_url('departamentos/buscarAJAX')?>");
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>", "<?php echo PERIODO?>");
  </script>
</body>
</html>