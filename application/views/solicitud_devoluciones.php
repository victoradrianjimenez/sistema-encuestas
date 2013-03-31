<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Devoluciones - <?php echo NOMBRE_SISTEMA?></title>
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
          <h3>Planes de mejora</h3>
          <p>En esta sección se permite acceder a un informe de plan de mejoras creados hasta el momento por una materia de una carrera en particular.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-devoluciones.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Devoluciones</h4>
          <form class="form-horizontal" action="<?php echo site_url('devoluciones/ver')?>" method="post">
    
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
  <script src="<?php echo base_url('js/formulario.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script>
    autocompletar_carrera($('#buscarCarrera'), "<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_materia($('#buscarMateria'), "<?php echo site_url('materias/buscarAJAX')?>");
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>", "<?php echo PERIODO?>");
  </script>
</body>
</html>