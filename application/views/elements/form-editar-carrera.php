<!-- Última revisión: 2012-02-01 2:33 p.m. -->

<h3><?php echo $titulo?></h3>
<form action="<?php echo $link?>" method="post">
    <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>"/>
    <div class="twelve columns">
      <label for="buscarDepartamento">Departamento: <span class="opcional">*</span></label>
      <div class="buscador">
        <input id="buscarDepartamento" type="text" autocomplete="off" value="<?php echo $departamento->nombre?>">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listaDepartamentos" name="idDepartamento" size="3" required >
          <?php if($departamento->idDepartamento):?>
          <option value="<?php echo $departamento->idDepartamento?>" selected>
            <?php echo $departamento->nombre.' (ID='.$departamento->idDepartamento.')'?>
          </option>
          <?php endif?>
        </select>
        <?php echo form_error('idDepartamento')?>
      </div>    
    </div>
    <div class="eight mobile-three columns">
      <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
      <input id="campoNombre" type="text" name="nombre" value="<?php echo $carrera->nombre?>" required />
      <?php echo form_error('Nombre'); ?>
    </div>
    <div class="four mobile-one columns">
      <label for="campoPlan">Plan: <span class="opcional">*</span></label>
      <input id="campoPlan" type="number" min="1900" max="2100" name="plan" value="<?php echo $carrera->plan?>" required />
      <?php echo form_error('Plan'); ?>
    </div>
    <div class="twelve columns">
      <label for="buscarUsuario">Director de carrera: </label>
      <div class="buscador">
        <input id="buscarUsuario" type="text" autocomplete="off" value="<?php echo $director->nombre.' '.$director->apellido?>">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listausuarios" name="idDirectorCarrera" size="3">
          <?php if($director->id):?>
          <option value="<?php echo $director->id?>" selected>
            <?php echo $director->nombre.' '.$director->apellido.' (ID='.$director->id.')'?>
          </option>
          <?php endif?>
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
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0]; //IdDepartamento
        var datos = columnas[1]; //Nombre
        //agregar fila a la lista desplegable
        $('#listaDepartamentos').append('<option value="'+id+'">'+datos+'</option>');
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
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listausuarios').append('<option value="'+id+'">'+datos+'</option>');
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