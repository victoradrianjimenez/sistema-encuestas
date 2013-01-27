<form action="<?php echo $link?>" method="post">
  <h3>Asociar docente</h3>
  <h5><?php echo $materia['Nombre'].' - Código '.$materia['Codigo']?></h5>
  <input type="hidden" name="IdMateria" value="<?php echo $materia['IdMateria']?>" />
  <label for="buscarPersona">Buscar persona: </label>
  <div class="buscador">
    <input id="buscarPersona" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
  </div>
  <select id="listaResultado" name="IdDocente" size="3">
  </select>
  <?php echo form_error('IdDocente')?>
  
  <label for="campoTipoAcceso">Tipo de acceso:</label>
  <select id="campoTipoAcceso" name="TipoAcceso">
    <option value="D">Docente</option>
    <option value="J">Jefe de cátedra</option>
  </select>
  <?php echo form_error('TipoAcceso')?>
  
  <label for="campoOrdenFormulario">Orden en el que aparece en el formulario:</label>
  <input id="campoOrdenFormulario" type="number" min="0" max="255" step="1" name="OrdenFormulario" />
  <?php echo form_error('OrdenFormulario')?>
  
  <label for="campoCargo">Cargo:</label>
  <input id="campoCargo" type="text" name="Cargo" />
  <?php echo form_error('Cargo')?>
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
  $('#buscarPersona').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('personas/buscarAJAX')?>", 
      data:{ Buscar: $('#buscarPersona').val() }
    }).done(function(msg){
      $('#listaResultado').empty();
      //separo los datos separados en filas
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<2) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listaResultado').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
</script>