<form action="<?php echo $link?>" method="post">
  <h3>Asociar materia</h3>
  <h5><?php echo $carrera['Nombre'].' - Plan '.$carrera['Plan']?></h5>
  <input type="hidden" name="IdCarrera" value="<?php echo $carrera['IdCarrera']?>" />
  <label for="buscarMateria">Buscar materia: </label>
  <div class="buscador">
    <input id="buscarMateria" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
  </div>
  <select id="listaResultado" class="hide" name="IdMateria" size="5">
  </select>
  <div class="row">         
    <div class="ten columns centered">
      <div class="six mobile-one columns push-one-mobile">
        <input class="button cancelar" type="button" value="Cancelar"/>
      </div>
      <div class="six mobile-one columns pull-one-mobile ">
        <input id="aceptarModalAsociar"  class="button" type="submit" name="submit" value="Aceptar" />
      </div>
    </div>
  </div>
</form>
<script>
  //realizo la busqueda de materias con AJAX
  $('#buscarMateria').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('materias/buscar')?>", 
      data:{ Buscar: $('#buscarMateria').val() }
    }).done(function(msg){
      //si el servidor no envia datos
      if (msg.length == 0){
        //ocultar listado
        $('#listaResultado').hide('fast');
        return;
      }
      //separo los datos separados en filas
      var filas = msg.split("\n");
      $('#listaResultado').empty().show('fast');
      for (var i=0; i<filas.length-1; i++){
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