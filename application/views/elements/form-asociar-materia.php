<form action="<?php echo site_url('carreras/asociarMateria')?>" method="post">
  <h3>Asociar materia</h3>
  <h5><?php echo $carrera['nombre'].' - Plan '.$carrera['plan']?></h5>
  <input type="hidden" name="idCarrera" value="<?php echo $carrera['idCarrera']?>" />
  <label for="buscarMateria">Buscar materia: </label>
  <div class="buscador">
    <input id="buscarMateria" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
    <select id="listaResultado" name="idMateria" size="3">
    </select>
    <?php echo form_error('idMateria')?>
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
  //realizo la busqueda de materias con AJAX
  $('#buscarMateria').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('materias/buscarAJAX')?>", 
      data:{ buscar: $(this).val() }
    }).done(function(msg){
      $('#listaResultado').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<6) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1]+' ('+columnas[2]+')';
        //agregar fila a la lista desplegable
        $('#listaResultado').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
</script>