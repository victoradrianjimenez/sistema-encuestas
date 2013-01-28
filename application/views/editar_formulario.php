<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar Formulario</title>
  
  <style>
    .Formularios{
      border: 1px solid #777777;
    }
    .Secciones li{
      border: 1px solid #2BA6CB;
    }
    .Preguntas li{
      border: 1px solid #CCCCCC;
    }
    li{
      list-style: none;
    }
    

    #agregarSeccion{
      font-size: 32px;
      float: right;
    }

    .barra-botones > a{
      font-size: 20px;
    }
    .barra-botones{
      float:right;
    }
    
    .Pregunta .barra-botones > a{
      color:#CCCCCC;
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
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <form action="<?php echo site_url('formularios/nuevo')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Formulario</h3>
            <label>Nombre:</label>
            <input type="text" name="Nombre" required />
            <label>Título:</label>
            <input type="text" name="Titulo" required />
          </div>
          <div class="nine mobile-two  columns">
            <label>Descripción:</label>
            <input type="text" name="Descripcion" />
          </div>
          <div class="three mobile-two columns">
            <label>Preguntas adicionales:</label>
            <input type="number" name="PreguntasAdicionales" min="0" max="255" step="1" value="10" />
          </div>
        </div>
        <div class="row Formularios">
          <div class="twelve columns">
            <div class="barra-botones">
              <a id="agregarSeccion" data-reveal-id="modalAgregarSeccion" title="Agregar sección..."><i class="foundicon-plus"></i></a>
            </div>
            <h3>Secciones</h3>
            <ul class="Secciones"></ul>
          </div>
        </div>
        <div class="row">
          <div class="two columns centered">
            <input id="Aceptar" class="button" type="submit" name="submit" value="Aceptar" />
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
  
  
  
  <div id="HTMLSeccion" class="hide">
    <li class="Seccion">
      <div class="barra-botones">
        <a class="subirSeccion" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarSeccion" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarSeccion" title="Eliminar" href=""><i class="foundicon-remove"></i></a>
        <a class="agregarPregunta" title="Agregar pregunta..." href=""><i class="foundicon-plus"></i></a>
      </div>
      <input type="hidden" name="TextoSeccion" value="" />
      <input type="hidden" name="DescripcionSeccion" value="" />
      <input type="hidden" name="TipoSeccion" value="" />
      <h3 class="texto"></h3>
      <h5 class="descripcion"></h5>
      <ul class="Preguntas"></ul>

    </li>
  </div>
  
  <div id="HTMLPregunta" class="hide">
    <li class="Pregunta">
      <div class="barra-botones">
        <a class="subirPregunta" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarPregunta" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarPregunta" title="eliminar" href=""><i class="foundicon-remove"></i></a>
      </div>
      
      <input type="hidden" name="IdPregunta" value="" />
      <p class="texto"></p>

    </li>
  </div>
  
  
  
  
  <!-- ventana modal para agregar una seccion -->
  <div id="modalAgregarSeccion" class="reveal-modal medium">
    <h3>Agregar sección</h3>
    <label>Texto:
      <input type="text" name="TextoSeccion" />
    </label>
    <label>Descripción:
      <input type="text" name="DescripcionSeccion"/>
    </label>
    <label>Tipo de Sección:
      <select name="TipoSeccion">
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
    <h3>Agregar sección</h3>
    <label>Buscar pregunta:
      <div class="buscador">
        <input id="buscarPregunta" type="text" autocomplete="off">
        <i class="gen-enclosed foundicon-search"></i>
        <select id="listaPreguntas" name="IdMateria" size="3">
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
      pTexto = contFormulario.find('input[name="TextoSeccion"]').val();
      pDescripcion = contFormulario.find('input[name="DescripcionSeccion"]').val();
      pTipo = contFormulario.find('select[name="TipoSeccion"]').val();
      if (pTexto=='' || pTipo=='') return;
      
      //tomo la plantilla de la seccion y la agrego al formulario creado
      HTMLSeccion = $('#HTMLSeccion').html();
      $('.Secciones').append(HTMLSeccion);
      nuevaSeccion = $('.Secciones').children().last();
      
      //actualizo valores la plantilla
      nuevaSeccion.find('.texto').html(pTexto);
      nuevaSeccion.find('.descripcion').html(pDescripcion);
      nuevaSeccion.find('input[name="TextoSeccion"]').val(pTexto);
      nuevaSeccion.find('input[name="DescripcionSeccion"]').val(pDescripcion);
      nuevaSeccion.find('input[name="TipoSeccion"]').val(pTipo);
      
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
      pIdPregunta = $('#listaPreguntas').val();
      pTexto = $('#listaPreguntas').children('[value="'+ pIdPregunta +'"]').text();
      if (pTexto=='' || pIdPregunta=='') return;
      
      //tomo la plantilla de la pregunta y la agrego al formulario
      HTMLPregunta = $('#HTMLPregunta').html(); 
      ContenedorPreguntas = ContenedorSeccionActual.find('.Preguntas');
      ContenedorPreguntas.append(HTMLPregunta);
      nuevaPregunta = ContenedorPreguntas.children().last();
      
      //actualizo la plantilla
      nuevaPregunta.find('.idPregunta').html(pIdPregunta);
      nuevaPregunta.find('.texto').html(pTexto);
      nuevaPregunta.find('input[name="IdPregunta"]').val(pIdPregunta);
      
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
        return false;s
      });
      
      //ocultar la ventana
      $(this).trigger('reveal:close'); //cerrar ventana
    });

    $('#buscarPregunta').keyup(function(){
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('preguntas/buscarAjax')?>", 
        data: { Buscar: $(this).val() }
      }).done(function(msg){
        $('#listaPreguntas').empty();
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<3) continue;
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
        $(this).find('input[name="TextoSeccion"]').attr('name', 'TextoSeccion_'+i);
        $(this).find('input[name="DescripcionSeccion"]').attr('name', 'DescripcionSeccion_'+i);
        $(this).find('input[name="TipoSeccion"]').attr('name', 'TipoSeccion_'+i);
        //por cada pregunta de la seccion
        $(this).find('.Preguntas').children().each(function(j){
          //le cambio los nombres a los campos para poder enviarlos. Se le agrega un numero al final.
          $(this).find('input[name="IdPregunta"]').attr('name', 'IdPregunta_'+i+'_'+j);
        });
      });
      $(this).submit();
    });
    
  
  </script>


</body>
</html>