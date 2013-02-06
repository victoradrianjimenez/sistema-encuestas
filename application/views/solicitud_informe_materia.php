<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Generar Informe por Materia</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <h3>Solicitar informe por asignatura</h3>
      <form action="<?php echo site_url('encuestas/')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <label for="listaMaterias">Asignatura: </label>
            <select id="listaMaterias" name="idMateria" required>
              <?php foreach ($materias as $materia) {
                echo '<option value="'.$materia->idMateria.'">'.$materia->nombre.'</option>';
              }?>
            </select>
            <?php echo form_error('idMateria')?>
            
            <label for="listaCarreras">Carrera: </label>
            <select id="listaCarreras" name="idCarrera" required>
            </select>
            <?php echo form_error('idCarrera')?>
            
            <label for="buscarEncuesta">Año: </label>
            <div class="buscador">
              <input id="buscarEncuesta" type="text" autocomplete="off">
              <i class="gen-enclosed foundicon-search"></i>
              <select id="listaEncuestas" name="encuesta" size="3" required>
              </select>
              <?php echo form_error('encuesta')?>
            </div>
          </div>
          <div class="twelve columns">
            <div class="row">    
              <input type="checkbox" name="indicesSecciones" />Incluir promedio de índices de secciones
            </div>
            <div class="row">    
              <input type="checkbox" name="indicesDocentes" />Incluir promedio de índices para cada docente
            </div>
            <div class="row">    
              <input type="checkbox" name="indicesGeneral" />Incluir indice general
            </div>
          </div>
        </div>
        <div class="row">         
          <div class="three mobile-two columns centered pull-one-mobile">
            <input class="button" type="submit" name="submit" value="Aceptar" />
          </div>
        </div>
      </form>
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
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
  
  <script>
    //realizo la busqueda de encuestas por AJAX
    $('#buscarEncuesta').keyup(function(){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('encuestas/buscarEncuestaAJAX')?>", 
        data: { buscar: $(this).val() }
      }).done(function(msg){
        $('#listaEncuestas').empty();
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<5) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0]+"_"+columnas[1];
          var datos = columnas[2]+"/"+columnas[3];
          //agregar fila a la lista desplegable
          $('#listaEncuestas').append('<option value="'+id+'">'+datos+'</option>');
        }
      });
    });

    var solicitarCarreras = function(){
      if ($('#listaMaterias').val() == '') return;
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('materias/listarCarrerasAJAX')?>", 
        data:{ idMateria: $('#listaMaterias').val() }
      }).done(function(msg){
        $('#listaCarreras').empty();
        var filas = msg.split("\n");
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0];
          var datos = columnas[1] +' ('+columnas[2]+')';
          //agregar fila a la lista desplegable
          $('#listaCarreras').append('<option value="'+id+'">'+datos+'</option>');
        }
      });
    };

    //lleno la lista de carreras al seleccionar la materia
    $('#listaMaterias').change(solicitarCarreras);

    //lleno la lista de carreras por primera vez al cargar la pagina
    solicitarCarreras();
  </script>
</body>
</html>