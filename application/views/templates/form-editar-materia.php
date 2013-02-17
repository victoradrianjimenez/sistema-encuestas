<!-- Última revisión: 2012-02-01 4:01 p.m. -->

<input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" required />
<div class="control-group">
  <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoNombre" type="text" name="nombre" value="<?php echo $materia->nombre?>" required />
    <?php echo form_error('nombre'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoCodigo">Código: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoCodigo" type="text" name="codigo" value="<?php echo $materia->codigo?>" required />
    <?php echo form_error('codigo'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoAlumnos">Cantidad de alumnos: </label>
  <div class="controls">
    <input class="input-xlarge" id="campoAlumnos" type="number" name="alumnos" min="0" step="1" value="<?php echo $materia->alumnos?>"/>
    <?php echo form_error('alumnos'); ?>
  </div>
</div>

<script>
  //ocultar mensaje de error al escribir
  $('input[type="text"], input[type="number"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>