<h3>Editar materia</h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="IdMateria" value="<?php echo $materia['IdMateria']?>"/>
    <div class="eight mobile-three columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="Nombre" value="<?php echo $materia['Nombre']?>"/>
      <?php echo form_error('Nombre'); ?>
    </div>
    <div class="four mobile-one columns">
      <label for="campoCodigo">Código: </label>
      <input id="campoCodigo" type="text" name="Codigo" value="<?php echo $materia['Codigo']?>"/>
      <?php echo form_error('Codigo'); ?>
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