<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ranking de Materias - <?php echo NOMBRE_SISTEMA?></title>
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
          <p>Esta sección permite acceder a un informe que contiene un resumen de las respuestas obtenidas para toda la faultad en una determinada encuesta.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 4;
            include 'templates/submenu-informes.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          
          <ul class="nav nav-tabs">
            <li><a href="<?php echo site_url('informes/facultad')?>">Informe por departamento</a></li>
            <li class="active"><a href="#">Ranking de Materias</a></li>
            <li><a href="<?php echo site_url('ranking/docentesFacultad')?>">Ranking de Docentes</a></li>
          </ul>

          <form class="form-horizontal" action="<?php echo site_url('ranking/materiasFacultad')?>" method="post">
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
  <script src="<?php echo base_url('js/formularios.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script>
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>", "<?php echo PERIODO?>");
  </script>
</body>
</html>