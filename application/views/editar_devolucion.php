<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar Devolución - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Planes de mejora</h3>
          <p>En esta sección puede crear un plan de mejoras propuesto para una materia, basado en los resultados de una encuesta.</p>
        </div>
      </div>
  
      <div class="row">
        <!-- SideBar -->
        
        <!-- Main -->
        <div class="span12">
          <form action="<?php echo site_url('devoluciones/nueva')?>" method="post">
            <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" required/>
              
            <label class="control-label" for="buscarMateria">Materia:</label>
            <div class="controls buscador">
              <input class="input-block-level" id="buscarMateria" name="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarMateria')?>" required /><i class="icon-search"></i>
              <input type="hidden" name="idMateria" required value="<?php echo $materia->idMateria?>"/>
              <?php echo form_error('idMateria')?>
            </div>
            
            <label class="control-label" for="buscarEncuesta">Año:</label>
            <div class="controls buscador">
              <input class="input-block-level" id="buscarEncuesta" name="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" value="<?php echo set_value('buscarEncuesta')?>" required /><i class="icon-search"></i>
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
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script>
    autocompletar_encuesta($('#buscarEncuesta'), "<?php echo site_url('encuestas/buscarAJAX')?>", "<?php echo PERIODO?>");
    autocompletar_materia($('#buscarMateria'), "<?php echo site_url('materias/buscarAJAX')?>");
  </script>
</body>
</html>