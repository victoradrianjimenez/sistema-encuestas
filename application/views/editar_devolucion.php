<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar Devolución</title>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <?php include 'templates/descripcion-devoluciones.php'?>
        </div>
      </div>
  
      <div class="row">
        <!-- SideBar -->
        
        <!-- Main -->
        <div class="span12">
          <h4>Nueva devolución</h4>
          <p>Asignatura: <?php echo $materia->nombre.' / '.$materia->codigo?></p>
          <form action="<?php echo site_url('devoluciones/nueva')?>" method="post">
            <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" required/>
            <fieldset>
              <legend>Evaluación de la cátedra sobre los resultados de las encuestas</legend>
              
              <label for="buscarEncuesta">Año:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarEncuesta" name="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo set_value('buscarEncuesta')?>">
                <input type="hidden" name="idEncuesta" required value="<?php echo $devolucion->idEncuesta?>"/>
                <input type="hidden" name="idFormulario" required value="<?php echo $devolucion->idFormulario?>"/>
                <?php echo form_error('idEncuesta')?>
              </div>
              
              <label for="campoFortalezas">Identifique las fortalezas del curso: </label>
              <textarea class="input-block-level" id="campoFortalezas"  name="fortalezas" rows="4"><?php echo $devolucion->fortalezas?></textarea>
              <?php echo form_error('fortalezas')?>
              
              <label for="campoDebilidades">Identifique las debilidades del curso: </label>
              <textarea class="input-block-level" id="campoDebilidades" name="debilidades" rows="4"><?php echo $devolucion->debilidades?></textarea>
              <?php echo form_error('debilidades')?>
              
              <label for="campoAlumnos">Reflexiones sobre las opiniones de los alumnos: </label>
              <textarea class="input-block-level" id="campoAlumnos" name="alumnos" rows="4"><?php echo $devolucion->alumnos?></textarea>
              <?php echo form_error('alumnos')?>
              
              <label for="campoDocentes">Reflexiones sobre el desempeño de los docentes: </label>
              <textarea class="input-block-level" id="campoDocentes" name="docentes" rows="4"><?php echo $devolucion->docentes?></textarea>
              <?php echo form_error('docentes')?>
              
              <label for="campoMejoras">Plan de mejoras propuesto: </label>
              <textarea class="input-block-level" id="campoMejoras" name="mejoras" rows="4"><?php echo $devolucion->mejoras?></textarea>
              <?php echo form_error('mejoras')?>
  
              <div>
                <input id="Aceptar" class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
              </div>
            </fieldset>
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script>
    autocompletar_encuesta("<?php echo site_url('encuestas/buscarAJAX')?>");
  </script>
</body>
</html>