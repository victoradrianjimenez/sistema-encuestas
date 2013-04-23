<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar Informe por Materia - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    #contenedor .form-horizontal .controls {margin-left: 70px}
    #contenedor .form-horizontal .control-label {width: 50px; float: left}
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
          <p>Esta sección permite acceder a un informe que contiene un resumen de las respuestas obtenidas por una materia en una determinada encuesta.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-informes.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Solicitar informe por asignatura</h4>
          <form class="form-horizontal" action="<?php echo site_url('informes/materia')?>" method="post">
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarCarrera" name="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarCarrera')?>" required><i class="icon-search"></i>
                <input type="hidden" name="idCarrera" value="<?php echo set_value('idCarrera')?>" required/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarMateria">Materia:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarMateria" name="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarMateria')?>" required><i class="icon-search"></i>
                <input type="hidden" name="idMateria" value="<?php echo set_value('idMateria')?>" required/>
                <?php echo form_error('idMateria')?>
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
                <label class="checkbox"><input type="checkbox" name="indicesSecciones" value="1" <?php echo set_checkbox('indicesSecciones', '1', TRUE)?> />Incluir promedio de índices de secciones</label>
                <label class="checkbox"><input type="checkbox" name="indicesDocentes" value="1" <?php echo set_checkbox('indicesDocentes', '1', TRUE)?> />Incluir promedio de índices para cada docente</label>
                <label class="checkbox"><input type="checkbox" name="indiceGlobal" value="1" <?php echo set_checkbox('indiceGlobal', '1', TRUE)?> />Incluir indice general</label>
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
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_carrera($('#buscarCarrera'), "<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_materia($('#buscarMateria'), "<?php echo site_url('carreras/buscarMateriasAJAX')?>");
    autocompletar_encuesta_materia($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarMateriaAJAX')?>", "<?php echo PERIODO?>");
  </script>
</body>
</html>