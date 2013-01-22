<h3>Editar departamento</h3>
<form action="<?php echo $link?>" method="post">
  <input type="hidden" name="IdDepartamento" value="<?php echo $departamento['IdDepartamento']?>" /> 
  <div class="twelve columns">
    <label for="campoNombre" for="campoNombre">Nombre: </label>
    <input type="text" id="campoNombre" name="Nombre" value="<?php echo $departamento['Nombre']?>" />
    <?php echo form_error('Nombre')?>
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
  </div>
</form>