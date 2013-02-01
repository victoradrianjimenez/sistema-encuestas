<h3>Crear nueva Encuesta</h3>
<form action="<?php echo site_url('encuestas/nueva')?>" method="post">
  <div class="twelve columns">
    <label for="buscarFormulario">Formulario: </label>
    <div class="buscador">
      <input id="buscarFormulario" type="text" autocomplete="off">
      <i class="gen-enclosed foundicon-search"></i>
      <select id="listaFormularios" name="idFormulario" size="3">
      </select>
      <?php echo form_error('idFormulario')?>
    </div>
  </div>
  <div class="eight columns">
    <label>Año: </label>
    <input type="number" name="anio" min="1900" max="2100" step="1" value="<?php echo date('Y')?>"/>
    <?php echo form_error('anio')?>
  </div>
  <div class="four columns">
    <label title="Período/Cuatrimestre">Período: </label>
    <input type="number" name="cuatrimestre" min="1" step="1" value="1" />
    <?php echo form_error('cuatrimestre')?>
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
  //realizo la busqueda de formularios con AJAX
  $('#buscarFormulario').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('formularios/buscarAJAX')?>", 
      data:{ buscar: $(this).val() }
    }).done(function(msg){
      $('#listaFormularios').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1]+' ('+columnas[2]+')';
        //agregar fila a la lista desplegable
        $('#listaFormularios').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
</script>