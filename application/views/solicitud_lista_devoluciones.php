<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Devoluciones</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
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
          <?php $item_submenu = 2;
            include 'templates/submenu-devoluciones.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Devoluciones</h4>
          <form class="form-horizontal" action="<?php echo site_url('devoluciones/listar')?>" method="post">
    
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idCarrera" required/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="controls">
              <input class="btn btn-primary" type="submit" name="submit" value="Listar Planes de Mejoras" />
              <!--<button class="nuevoPlan btn btn-primary" href="#modalNuevoPlan" role="button" data-toggle="modal">Nuevo Plan de Mejoras...</button> -->
              <a class="btn btn-primary" href="<?php echo site_url('devoluciones/nueva')?>">Nuevo Plan de Mejoras</a>
              
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalNuevoPlan" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Nuevo Plan de Mejoras</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('devoluciones/nueva')?>" method="post">
      <div class="modal-body">
        <h5></h5>
        <div class="control-group">
          <label class="control-label" for="buscarMateria">Materia:</label>
          <div class="controls">
            <input class="input-block-level" id="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" required>
            <input type="hidden" name="idMateria" required/>
            <?php echo form_error('idMateria')?>
          </div>
        </div>
        <div class="control-group">
          <label for="buscarEncuesta">Año:</label>
          <div class="controls">
            <input class="input-block-level" id="buscarEncuesta" name="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo set_value('buscarEncuesta')?>">
            <input type="hidden" name="idEncuesta" required value=""/>
            <input type="hidden" name="idFormulario" required value=""/>
            <?php echo form_error('idEncuesta')?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_carrera($('#buscarCarrera'), "<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_materia($('#buscarMateria'), "<?php echo site_url('materias/buscarAJAX')?>");
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>");
    $('.nuevoPlan').click(function(){
      $("#modalNuevoPlan").modal();
      return false;
    });
  </script>
</body>
</html>