<h3>Editar departamento</h3>
<form action="<?php echo $link?>" method="post">
  <input type="hidden" name="IdDepartamento" value="<?php echo $departamento['IdDepartamento']?>" required /> 
  <div class="twelve columns">
    <label for="campoNombre">Nombre: </label>
    <input type="text" id="campoNombre" name="Nombre" value="<?php echo $departamento['Nombre']?>" required />
    <?php echo form_error('Nombre')?>
    
    <label for="buscarPersona">Jefe de Departamento: </label>
    <div class="buscador">
      <input id="buscarPersona" type="text" autocomplete="off">
      <i class="gen-enclosed foundicon-search"></i>
      <select id="listaPersonas" name="IdJefeDepartamento" size="3">
      </select>
      <?php echo form_error('IdJefeDepartamento')?>
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
  </div>
</form>
<script>
  //realizo la busqueda de personas con AJAX
  $('#buscarPersona').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('personas/buscar')?>", 
      data:{ Buscar: $(this).val() }
    }).done(function(msg){
      $('#listaPersonas').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<2) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listaPersonas').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
</script>