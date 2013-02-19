<form action="<?php echo site_url('claves/generar')?>" method="post">
  <h3>Generar claves de acceso</h3>
  <h5><?php echo $encuesta['año'].' ('.$encuesta['cuatrimestre'].')'?></h5>
  
  <input type="hidden" name="idEncuesta" value="<?php echo $encuesta['idEncuesta']?>" />
  <input type="hidden" name="idFormulario" value="<?php echo $encuesta['idFormulario']?>" />
  
  <label for="buscarCarrera">Carrera: </label>
  <div class="buscador">
    <input id="buscarCarrera" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
  </div>
  <select id="listaCarreras" name="idCarrera" size="2">
  </select>
  <?php echo form_error('idCarrera')?>
  
  <label for="buscarMateria">Materia: </label>
  <div class="buscador">
    <input id="buscarMateria" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
  </div>
  <select id="listaMaterias" name="idMateria" size="2">
  </select>
  <?php echo form_error('idMateria')?>
  
  <label for="tipoAcceso">Tipo de clave: </label>
  <select id="tipoAcceso" name="tipo">
    <option value="E">Encuesta anónima</option>
    <option value="O">Encuesta obligatorias(usando CX)</option>
    <option value="R">Registrar alumno</option>
  </select>
  <?php echo form_error('tipo')?>
  
  <div>
    <label for="cantidad">Cantidad de claves a generar:</label>
    <input id="cantidad" type="number" name="cantidad" value="1"/>
    <?php echo form_error('cantidad')?>
  </div>
  <span id="mensajeCantidad" class="hide" >Se generarán tantas claves como alumnos haya registrados. <a href="#">(Ver)</a></span>

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
  //realizo la busqueda de carreras con AJAX
  $('#buscarCarrera').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('carreras/buscarAJAX')?>", 
      data:{ buscar: $(this).val() }
    }).done(function(msg){
      $('#listaCarreras').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1]+' ('+columnas[2]+')';
        //agregar fila a la lista desplegable
        $('#listaCarreras').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
  
   //realizo la busqueda de materias con AJAX
  $('#buscarMateria').keyup(function(){
    var idCarrera = $('#listaCarreras').val();
    if (idCarrera == '') return;
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('carreras/buscarMateriasAJAX')?>", 
      data:{ 
        idCarrera: idCarrera,
        buscar: $(this).val() 
      }
    }).done(function(msg){
      $('#listaMaterias').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1]+' ('+columnas[2]+')';
        //agregar fila a la lista desplegable
        $('#listaMaterias').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
  $('#listaMaterias').change(function(){
    var idCarrera = $('#listaCarreras').val();
    var idMateria = $(this).val();
    var tipo = $('#tipoAcceso').val();
    if (idCarrera == '' || idMateria == '') return;
    if (tipo == 'E' || tipo == 'R'){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('materias/cantidadAlumnosAJAX')?>", 
        data:{idMateria: idMateria}
      }).done(function(msg){
        $('#cantidad').attr('value',Number(msg));
      });
    }

  });
  
  $('#tipoAcceso').change(function(){
    var tipo = $('#tipoAcceso').val();
    if (tipo == 'E' || tipo == 'R'){
      $('#mensajeCantidad').hide();
      $('#cantidad').parent().show();
    }
    else{
      $('#cantidad').parent().hide();
      $('#mensajeCantidad').show();
    }
  });
  
</script>