<h3>Editar carrera</h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="IdCarrera" value="<?php echo $carrera['IdCarrera']?>"/>
    <div class="twelve columns">
      <label for="listaDepartamentos">Departamento: </label>
      <select id="listaDepartamentos" name="IdDepartamento"></select>
    </div>
    <div class="nine mobile-three columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="Nombre" value="<?php echo $carrera['Nombre']?>"/>
      <?php echo form_error('Nombre'); ?>
    </div>
    <div class="three mobile-one columns">
      <label for="campoPlan">Plan: </label>
      <input id="campoPlan" type="number" min="1900" max="2100" name="Plan" value="<?php echo $carrera['Plan']?>"/>
      <?php echo form_error('Plan'); ?>
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
  //listo los departamentos
  $.ajax({
      type: "POST", 
      url: "<?php echo site_url('departamentos/listarAjax')?>", 
      data: {}
    }).done(function(msg){
      $('#listaDepartamentos').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0]; //IdDepartamento
        var datos = columnas[1]; //Nombre
        //agregar fila a la lista desplegable
        $('#listaDepartamentos').append('<option value="'+id+'">'+datos+'</option>');
      }
      //selecciono el item que corresponde
      $('#listaDepartamentos').val(<?php echo $carrera['IdDepartamento']?>);
    });
</script>