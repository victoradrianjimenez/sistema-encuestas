<h3><?php echo $titulo?></h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="idMateria" value="<?php echo $materia['idMateria']?>" required />
    <div class="eight mobile-three columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="nombre" value="<?php echo $materia['nombre']?>" required />
      <?php echo form_error('nombre'); ?>
    </div>
    <div class="four mobile-one columns">
      <label for="campoCodigo">Código: </label>
      <input id="campoCodigo" type="text" name="codigo" value="<?php echo $materia['codigo']?>" required />
      <?php echo form_error('codigo'); ?>
    </div>
    <div class="row">         
      <div class="ten columns centered">
        <div class="six mobile-one columns push-one-mobile">
          <input class="button cancelar" type="button" value="Cancelar"/>
        </div>
        <div class="six mobile-one columns pull-one-mobile ">
          <input class="button" type="submit" name="submit" value="Aceptar" />
        </div>
      </div>
    </div>
</form>
<script>
  //ocultar mensaje de error al escribir
  $('input[type="text"]').keyup(function(){
    $(this).next('small.error').hide('fast');
  });
  $('input[type="number"]').change(function(){
    $(this).next('small.error').hide('fast');
  });
</script>