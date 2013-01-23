<h3>Crear nueva Encuesta</h3>
<form action="<?php echo site_url('encuestas/nueva')?>" method="post">
  <div class="twelve columns">
    <label>Formulario: </label>
    <select id="listaFormularios" name="IdFormulario">
    </select>
  </div>
  <div class="eight columns">
    <label>Año: </label>
    <input type="number" name="Anio" min="1900" max="2100" step="1" value="<?php echo date('Y')?>"/>
  </div>
  <div class="four columns">
    <label title="Período/Cuatrimestre">Período: </label>
    <input type="number" name="Cuatrimestre" min="1" step="1" value="1" />
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
  $.ajax({
    type: "POST", 
    url: "<?php echo site_url('formularios/listarAJAX')?>", 
    data:{}
  }).done(function(msg){
    //si el servidor no envia datos
    if (msg.length == 0) return;
    //separo los datos separados en filas
    var filas = msg.split("\n");
    $('#listaFormularios').empty();
    for (var i=0; i<filas.length-1; i++){
      //separo datos en columnas
      var columnas = filas[i].split("\t");
      var id = columnas[0];
      var datos = columnas[1]+' ('+columnas[2]+')';
      //agregar fila a la lista desplegable
      $('#listaFormularios').append('<option value="'+id+'">'+datos+'</option>');
    }
  });
</script>