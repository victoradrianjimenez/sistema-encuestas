<!DOCTYPE html>
<!-- Última revisión: 2012-02-05 2:01 p.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar Formulario</title>
  <style>
    .Secciones li{
      border: 1px solid #2BA6CB;
      padding: 5px;
      margin: 10px 0;
    }
    .barra-botones{
      float:right;
      margin: 0;
    }
    li{list-style: none;}
    .Preguntas{margin: 5px;}
    .Preguntas li{border: 1px solid #CCCCCC;}
    .Pregunta .barra-botones > a{
      font-size: 20px;
      color:#CCCCCC;
      margin: 0;
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

  <div class="row">
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>  
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <form action="<?php echo site_url('formularios/nuevo')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Formulario</h3>
            <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
            <input id="campoNombre" type="text" name="nombre" required />
            <label for="campoTitulo">Título: <span class="opcional">*</span></label>
            <input id="campoTitulo" type="text" name="titulo" required />
          </div>
          <div class="nine mobile-two  columns">
            <label for="campoDescripcion">Descripción: </label>
            <input id="campoDescripcion" type="text" name="descripcion" />
          </div>
          <div class="three mobile-two columns">
            <label for="campoAdicionales">Preguntas adicionales: <span class="opcional">*</span></label>
            <input id="campoAdicionales" type="number" name="preguntasAdicionales" min="0" max="255" step="1" value="10" />
          </div>
        </div>
        <div class="row">
          <div class="twelve columns">
            <div class="row Formularios">
              <div class="twelve columns">
                <h3 style="float:right"><a id="agregarSeccion" data-reveal-id="modalAgregarSeccion" title="Agregar sección..."><i class="foundicon-plus"></i></a></h3>
                <h3>Secciones</h3>
              </div>
            </div>
            <ul class="Secciones"></ul>
          </div>
          <div class="row">
            <div class="two columns centered">
              <input id="Aceptar" class="button" type="submit" name="submit" value="Aceptar" />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <div id="HTMLSeccion" class="hide">
    <li class="Seccion">
      <h3 class="barra-botones">
        <a class="subirSeccion" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarSeccion" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarSeccion" title="Eliminar" href=""><i class="foundicon-remove"></i></a>
        <a class="agregarPregunta" title="Agregar pregunta..." href=""><i class="foundicon-plus"></i></a>
      </h3>
      <input type="hidden" name="textoSeccion" value="" />
      <input type="hidden" name="descripcionSeccion" value="" />
      <input type="hidden" name="tipoSeccion" value="" />
      <h4 class="texto"></h4>
      <h6 class="descripcion"></h6>
      <ul class="Preguntas"></ul>
    </li>
  </div>
  
  <div id="HTMLPregunta" class="hide">
    <li class="Pregunta">
      <h3 class="barra-botones">
        <a class="subirPregunta" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarPregunta" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarPregunta" title="Eliminar" href=""><i class="foundicon-remove"></i></a>
      </h3>
      <input type="hidden" name="idPregunta" value="" />
      <p class="texto"></p>
    </li>
  </div>
  
  <!-- ventana modal para agregar una seccion -->
  <div id="modalAgregarSeccion" class="reveal-modal medium">
    <h3>Agregar sección</h3>
    <label>Texto:
      <input type="text" name="textoSeccion" />
    </label>
    <label>Descripción:
      <input type="text" name="descripcionSeccion"/>
    </label>
    <label>Tipo de Sección:
      <select name="tipoSeccion">
        <option value="N">Normal</option>
        <option value="D">Docente</option>
      </select>
    </label>
    <div>
      <a class="button agregarSeccion">Agregar</a>
      <a class="close-reveal-modal">&#215;</a>
    </div>
  </div>
  
  <!-- ventana modal para agregar una pregunta -->
  <div id="modalAgregarPregunta" class="reveal-modal medium">
    <h3>Agregar pregunta</h3>
    <label>Buscar pregunta:
      <div class="buscador">
        <input id="buscarPregunta" type="text" autocomplete="off">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listaPreguntas" name="idMateria" size="3">
        </select>
      </div>
    </label>
    <a class="button agregarPregunta">Agregar</a>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>

  <script>
    var ContenedorSeccionActual=null;
  
    $('.nuevaSeccion').click(function(){
      $(this).siblings('.editarSeccion').show('fast');
    });
    
    $('.agregarSeccion').click(function(){
      //busco el contenedor del formulario de agregar seccion
      contFormulario = $('#modalAgregarSeccion');
      
      //leo los datos ingresados
      ptexto = contFormulario.find('input[name="textoSeccion"]').val();
      pdescripcion = contFormulario.find('input[name="descripcionSeccion"]').val();
      ptipo = contFormulario.find('select[name="tipoSeccion"]').val();
      if (ptexto=='' || ptipo=='') return;
      
      //tomo la plantilla de la seccion y la agrego al formulario creado
      HTMLSeccion = $('#HTMLSeccion').html();
      $('.Secciones').append(HTMLSeccion);
      nuevaSeccion = $('.Secciones').children().last();
      
      //actualizo valores la plantilla
      nuevaSeccion.find('.texto').html(ptexto);
      nuevaSeccion.find('.descripcion').html(pdescripcion);
      nuevaSeccion.find('input[name="textoSeccion"]').val(ptexto);
      nuevaSeccion.find('input[name="descripcionSeccion"]').val(pdescripcion);
      nuevaSeccion.find('input[name="tipoSeccion"]').val(ptipo);
      
      //agrego gestor de eventos de los nuevos botones
      nuevaSeccion.find('.subirSeccion').click(function(){
        Contenedor = $(this).parentsUntil('li.Seccion').parent();
        Contenedor.prev().before(Contenedor);
        return false;
      });
      nuevaSeccion.find('.bajarSeccion').click(function(){
        Contenedor = $(this).parentsUntil('li.Seccion').parent();
        Contenedor.next().after(Contenedor);
        return false;
      });
      nuevaSeccion.find('.eliminarSeccion').click(function(){
        Contenedor = $(this).parentsUntil('li.Seccion').parent();
        Contenedor.hide('fast', function(){$(this).remove();});
        return false;
      });
      nuevaSeccion.find('.agregarPregunta').click(function(){
        ContenedorSeccionActual = $(this).parentsUntil('li.Seccion').parent(); //variable global
        $("#modalAgregarPregunta").reveal();
        return false;
      });
 
      //ocultar ventana
      $(this).trigger('reveal:close'); //cerrar ventana
    });
    
    
    $('.agregarPregunta').click(function(){
      //busco el contenedor que tiene los datos de la nueva pregunta
      contFormulario = $('#modalAgregarPregunta');
      
      //leo los datos de la seccion
      pidPregunta = $('#listaPreguntas').val();
      ptexto = $('#listaPreguntas').children('[value="'+ pidPregunta +'"]').text();
      if (ptexto=='' || pidPregunta=='') return;
      
      //tomo la plantilla de la pregunta y la agrego al formulario
      HTMLPregunta = $('#HTMLPregunta').html(); 
      ContenedorPreguntas = ContenedorSeccionActual.find('.Preguntas');
      ContenedorPreguntas.append(HTMLPregunta);
      nuevaPregunta = ContenedorPreguntas.children().last();
      
      //actualizo la plantilla
      nuevaPregunta.find('.idPregunta').html(pidPregunta);
      nuevaPregunta.find('.texto').html(ptexto);
      nuevaPregunta.find('input[name="idPregunta"]').val(pidPregunta);
      
      //agrego gestor de eventos de los nuevos botones
      nuevaPregunta.find('.subirPregunta').click(function(){
        Contenedor = $(this).parentsUntil('li.Pregunta').parent();
        Contenedor.prev().before(Contenedor);
        return false;
      });
      nuevaPregunta.find('.bajarPregunta').click(function(){
        Contenedor = $(this).parentsUntil('li.Pregunta').parent();
        Contenedor.next().after(Contenedor);
        return false;
      });
      nuevaPregunta.find('.eliminarPregunta').click(function(){
        Contenedor = $(this).parentsUntil('li.Pregunta').parent();
        Contenedor.hide('fast', function(){$(this).remove();});
        return false;
      });
      //ocultar la ventana
      $(this).trigger('reveal:close'); //cerrar ventana
    });

    $('#buscarPregunta').keyup(function(){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('preguntas/buscarAjax')?>", 
        data: { buscar: $(this).val() }
      }).done(function(msg){
        $('#listaPreguntas').empty();
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<5) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0]; //IdPregunta
          var datos = columnas[2]; //Texto
          //agregar fila a la lista desplegable
          $('#listaPreguntas').append('<option value="'+id+'">'+datos+'</option>');
        }
      })
    });
    
    $('#Aceptar').click(function(){      
      //por cada seccion creada
      $('.Secciones').children().each(function(i){
        //le cambio los nombres a los campos para poder enviarlos. Se le agrega un numero al final.
        $(this).find('input[name="textoSeccion"]').attr('name', 'textoSeccion_'+i);
        $(this).find('input[name="descripcionSeccion"]').attr('name', 'descripcionSeccion_'+i);
        $(this).find('input[name="tipoSeccion"]').attr('name', 'tipoSeccion_'+i);
        //por cada pregunta de la seccion
        $(this).find('.Preguntas').children().each(function(j){
          //le cambio los nombres a los campos para poder enviarlos. Se le agrega un numero al final.
          $(this).find('input[name="idPregunta"]').attr('name', 'idPregunta_'+i+'_'+j);
        });
      });
      $(this).submit();
    });
  </script>
</body>
</html>