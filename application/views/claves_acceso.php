<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Claves de Acceso - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    .container .form-horizontal .controls {margin-left: 120px}
    .container .form-horizontal .control-label {width: 100px; float: left}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Encuestas</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de las encuestas y claves de acceso.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 2;
            include 'templates/submenu-encuestas.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Ingrese los datos de la materia y encuesta</h4>
          <form class="form-horizontal" action="<?php echo site_url('claves/ver')?>" method="post">
    
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarCarrera" name="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo set_value('buscarCarrera')?>"><i class="icon-search"></i>
                <input type="hidden" name="idCarrera" required value="<?php echo set_value('idCarrera')?>"/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarMateria">Materia:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarMateria" name="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo set_value('buscarMateria')?>"><i class="icon-search"></i>
                <input type="hidden" name="idMateria" required value="<?php echo set_value('idMateria')?>"/>
                <?php echo form_error('idMateria')?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="buscarEncuesta">Año/<?php echo PERIODO?>:</label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarEncuesta" name="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo set_value('buscarEncuesta')?>"><i class="icon-search"></i>
                <input type="hidden" name="idEncuesta" required value="<?php echo set_value('idEncuesta')?>"/>
                <?php echo form_error('idEncuesta')?>
                <input type="hidden" name="idFormulario" required value="<?php echo set_value('idFormulario')?>"/>
              </div>
            </div>
            <div class="controls">
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
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script>
    autocompletar_carrera($('#buscarCarrera'), "<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_materia($('#buscarMateria'), "<?php echo site_url('carreras/buscarMateriasAJAX')?>");
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>", "<?php echo PERIODO?>");
  </script>
</body>
</html>