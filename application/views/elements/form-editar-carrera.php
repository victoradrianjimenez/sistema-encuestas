<h3><?php echo $titulo?></h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="idCarrera" value="<?php echo $carrera['idCarrera']?>"/>
    <div class="twelve columns">
      <label for="buscarDepartamento">Departamento: </label>
      <div class="buscador">
        <input id="buscarDepartamento" type="text" autocomplete="off">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listaDepartamentos" name="idDepartamento" size="3" required >
          <option value="<?php echo $carrera['idDepartamento']?>" selected>(Departamento actual)</option>
          <option value="">(Nunguno)</option>
        </select>
        <?php echo form_error('idDepartamento')?>
      </div>    
    </div>
    <div class="eight mobile-three columns">
      <label for="campoNombre">Nombre: </label>
      <input id="campoNombre" type="text" name="nombre" value="<?php echo $carrera['nombre']?>" required />
      <?php echo form_error('Nombre'); ?>
    </div>
    <div class="four mobile-one columns">
      <label for="campoPlan">Plan: </label>
      <input id="campoPlan" type="number" min="1900" max="2100" name="plan" value="<?php echo $carrera['plan']?>" required />
      <?php echo form_error('Plan'); ?>
    </div>
    <div class="twelve columns">
      <label for="buscarUsuario">Director de carrera: </label>
      <div class="buscador">
        <input id="buscarUsuario" type="text" autocomplete="off">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listausuarios" name="idDirectorCarrera" size="3">
          <option value="<?php echo $carrera['idDirectorCarrera']?>" selected>(Director de carrera actual)</option>
          <option value="">(Nunguno)</option>
        </select>
        <?php echo form_error('idDirectorCarrera')?>
      </div>
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
      url: "<?php echo site_url('departamentos/buscarAJAX')?>", 
      data: { buscar: $(this).val() }
    }).done(function(msg){
      $('#listaDepartamentos').empty();
      var filas = msg.split("\n");
      var cnt = 0;
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<6) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0]; //IdDepartamento
        var datos = columnas[1]; //Nombre
        //agregar fila a la lista desplegable
        $('#listaDepartamentos').append('<option value="'+id+'">'+datos+'</option>');
        cnt++;
      }
      if(cnt==0){
        $('#listaDepartamentos').append('<option value="' + <?php echo $carrera['idDepartamento']?> + '" selected>(Departamento actual)</option>');
        $('#listaDepartamentos').append('<option value="">(Nunguno)</option>');
      }
      $('#listaDepartamentos').children().first().attr('selected','');
    });
  });

  //realizo la busqueda de usuarios con AJAX
  $('#buscarUsuario').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('usuarios/buscarAJAX')?>", 
      data:{ buscar: $(this).val() }
    }).done(function(msg){
      $('#listausuarios').empty();
      var filas = msg.split("\n");
      var cnt = 0;
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<2) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listausuarios').append('<option value="'+id+'">'+datos+'</option>');
        cnt++;
      }
      if(cnt==0){
        $('#listausuarios').append('<option value="' + <?php echo $carrera['idDirectorCarrera']?> + '" selected>(Director de carrera actual)</option>');
        $('#listausuarios').append('<option value="">(Nunguno)</option>');
      }
      $('#listausuarios').children().first().attr('selected','');
    });
  });
  
  $('#listausuarios').change(function(){
    textoOpcion = $(this).children('option[selected]').text();
    $('#buscarUsuario').val(textoOpcion);
    $(this).next('small.error').hide('fast');
  });
  
  $('#listaDepartamentos').change(function(){
    textoOpcion = $(this).children('option[selected]').text();
    $('#buscarDepartamento').val(textoOpcion);
    $(this).next('small.error').hide('fast');
  });
  
  //ocultar mensaje de error al escribir
  $('input[type="text"]').keyup(function(){
    $(this).next('small.error').hide('fast');
  });
  $('input[type="number"]').change(function(){
    $(this).next('small.error').hide('fast');
  });
</script>