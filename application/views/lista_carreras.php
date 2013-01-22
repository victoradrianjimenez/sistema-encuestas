<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Carreras</title>
  
  <style>
    .buscador{
      position: relative;
    }
    .buscador i{
      position: absolute; right: 0; top:0; margin:5px; font-size: 20px; color: #F2F2F2;
    }
    i:hover{
      color:#1E728C;
    }
    
    a.button{
      width: 100%;
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
          
          <h4>Carreras</h4>
          <?php if(isset($departamento)):?>
            <h6>
              <?php echo $departamento['Nombre']?>
              <a href="<?php echo site_url('carreras/listar')?>">(Ver todas)</a>              
            </h6>
          <?php endif ?>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron carreras.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Nombre</th>
                <th>Plan</th>
                <th>Departamento</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><a href="<?php echo site_url("carreras/editar/".$fila['IdCarrera'])?>"><?php echo $fila['Nombre']?></a></td>
                  <td><?php echo $fila['Plan']?></td>
                  <td><?php echo $fila['Departamento']?></td>
                  <td>
                    <a href="<?php echo site_url("carreras/modificar/".$fila['IdCarrera'])?>">Editar</a> /
                    <a href="<?php echo site_url("carreras/eliminar/".$fila['IdCarrera'])?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="three mobile-one columns">
          <a class="button" href="<?php echo site_url("carreras/nueva")?>">Nueva Carrera</a>
        </div>
        <?php if(isset($departamento)):?>
          <div class="three mobile-one columns end">
            <a class="button" id="asociarCarrera">Asociar carrera</a>
          </div>
        <?php endif?>          
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
  
  
  
  <?php if(isset($departamento)):?>
    <div id="modalAsociar" class="reveal-modal medium">
      <h3>Asociar carrera</h3>
      <h5><?php echo $departamento['Nombre']?></h5>
      <label for="buscarModalAsociar">Buscar carrera: </label>
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
            <input class="button" type="submit" name="submit" value="Aceptar" />
          </div>
        </div>
      </div>
      <a class="close-reveal-modal">&#215;</a>
    </div>
  <?php endif?>
    
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
  
  <script>
    $('#asociarCarrera').click(function(){
      //mostrar ventana
      $("#modalAsociar").reveal();
    });
    
    $('#cerrarModalAsociar').click(function(){
      //cerrar ventana
      $('#modalAsociar').trigger('reveal:close'); 
    });
    
    $('#buscarModalAsociar').keyup(function(){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('carreras/buscar')?>", 
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
          $('#listaModalAsociar').append('<option id="'+id+'">'+datos+'</option>');
        }
      });
    });
  </script>
  
  
</body>
</html>