<h3>Editar carrera</h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="IdCarrera" value="<?php echo $carrera['IdCarrera']?>"/>
    <div class="twelve columns">
      <label for="buscarDepartamento">Departamento: </label>
      <div class="buscador">
        <input id="buscarDepartamento" type="text" autocomplete="off">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listaDepartamentos" name="IdDepartamento" size="3">
        </select>
        <?php echo form_error('IdDepartamento')?>
      </div>    
    </div>
    <div class="eight mobile-three columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="Nombre" value="<?php echo $carrera['Nombre']?>" required />
      <?php echo form_error('Nombre'); ?>
    </div>
    <div class="four mobile-one columns">
      <label for="campoPlan">Plan: </label>
      <input id="campoPlan" type="number" min="1900" max="2100" name="Plan" value="<?php echo $carrera['Plan']?>" required />
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
  //realizo la busqueda de departamentos con AJAX
  $('#buscarDepartamento').keyup(function(){
    $.ajax({
        type: "POST", 
        url: "<?php echo site_url('departamentos/buscarAjax')?>", 
        data: { Buscar: $(this).val() }
      }).done(function(msg){
        $('#listaDepartamentos').empty();
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<2) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0]; //IdDepartamento
          var datos = columnas[1]; //Nombre
          //agregar fila a la lista desplegable
          $('#listaDepartamentos').append('<option value="'+id+'">'+datos+'</option>');
        }
      });
    });
</script>