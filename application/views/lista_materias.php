<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Materias</title>
  <style>
    .buscador{
      position: relative;
    }
    .buscador i{
      position: absolute; right: 0; top:0; margin:5px; font-size: 20px; color: #F2F2F2;
    }
    
    .button-group li a{
      margin-right: 5px;
    }
    
  </style>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>
  
  <!-- Main Section -->
  <div class="row">
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <div class="row">
        <div class="twelve columns">
          <?php if(isset($carrera)):?>
            <h3>Carrera</h3>
            <h6>
              <?php echo $carrera['Nombre']?> - Plan <?php echo $carrera['Plan'] ?>
              
            </h6>
          <?php endif ?>
          <h4>Materias</h4>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Nombre</th>
                <th>Codigo</th>
                <th>Alumnos</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><?php echo $fila['Nombre']?></a></td>
                  <td><?php echo $fila['Codigo']?></td>
                  <td><?php echo $fila['Alumnos']?></td>
                  <td>
                    <a href="<?php echo site_url("materias/modificar/".$fila['IdMateria'])?>">Editar</a> /
                    <a href="<?php echo site_url("materias/eliminar/".$fila['IdMateria'])?>">Eliminar</a> /
                    <a href="<?php echo site_url("materias/docentes/".$fila['IdMateria'])?>">Docentes</a> /
                    <a href="<?php echo site_url("materias/docentes/".$fila['IdMateria'])?>" title="Quitar asociación de la materia con la carrera">Quitar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <ul class="button-group">
            <li><a class="button" href="<?php echo site_url("materias/nueva")?>">Nueva Materia</a></li>
          <?php if(isset($carrera)):?>
            <li><a class="button" href="<?php echo site_url('carreras/listar')?>">Cambiar de carrera</a></li>
            <li><a class="button" data-reveal-id="modalAsociar">Asociar materia</a></li>
          <?php else:?>
            <li><a class="button" href="<?php echo site_url('carreras/listar')?>">Seleccionar carrera</a></li>
          <?php endif?>
        </div>
      </div>
    </div>

    <!-- Nav Sidebar -->
    <div class="three columns pull-nine">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>    
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  
  <!-- ventana modal para asociar materias a la carrera -->
  <?php if(isset($carrera)):?>
    <div id="modalAsociar" class="reveal-modal medium">
      <h3>Asociar materia</h3>
      <h5><?php echo $carrera['Nombre'].' - Plan '.$carrera['Plan']?></h5>
      <label for="buscarModalAsociar">Buscar materia: </label>
      <div class="buscador">
        <input id="buscarModalAsociar" type="text">
        <i class="gen-enclosed foundicon-search"></i>
      </div>
      <select id="listaModalAsociar" class="hide" size="5">
      </select>
      <div class="row">         
        <div class="ten columns centered">
          <div class="six mobile-one columns push-one-mobile">
            <input id="cerrarModalAsociar" class="button" type="button" value="Cancelar"/>
          </div>
          <div class="six mobile-one columns pull-one-mobile ">
            <input id="aceptarModalAsociar"  class="button" type="submit" name="submit" value="Aceptar" />
          </div>
        </div>
      </div>
      <a class="close-reveal-modal">&#215;</a>
    </div>
  <?php endif?>
    
  <div id="modalMensaje" class="reveal-modal small">
    <h3>Resultado de la operación:</h3>
    <h5></h5>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
  
  <script>
    $('#cerrarModalAsociar').click(function(){
      $('#modalAsociar').trigger('reveal:close'); //cerrar ventana
    });
    
    $('#aceptarModalAsociar').click(function(){
      
      var IdMateria = $('#listaModalAsociar').val();
      if (IdMateria == 0) return;
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('materias/asociar')?>", 
        data:{ IdMateria: IdMateria, IdCarrera: <?php echo $carrera['IdCarrera']?> }
      }).done(function(msg){
        $("#modalMensaje h5").html((msg != 'ok')?msg:'La operación se realizó con éxito.');
        $("#modalMensaje").reveal();
      });
    });
    
    $('#buscarModalAsociar').keyup(function(){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('materias/buscar')?>", 
        data:{ Buscar: $('#buscarModalAsociar').val() }
      }).done(function(msg){
        //si el servidor no envia datos
        if (msg.length == 0){
          //ocultar listado
          $('#listaModalAsociar').hide('fast');
          return;
        }
        //separo los datos separados en filas
        var filas = msg.split("\n");
        $('#listaModalAsociar').empty().show('fast');
        for (var i=0; i<filas.length-1; i++){
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0];
          var datos = columnas[1]+' ('+columnas[2]+')';
          //agregar fila a la lista desplegable
          $('#listaModalAsociar').append('<option value="'+id+'">'+datos+'</option>');
        }
      });
    });
  </script>
</body>
</html>