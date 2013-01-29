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
    .Opciones li{
      border: 1px solid #CCCCCC;
    }
    li{
      list-style: none;
    }

    .barra-botones > a{
      font-size: 20px;
    }
    .barra-botones{
      float:right;
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
      <form action="<?php echo site_url('preguntas/nuevo')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Pregunta</h3>
            <label>Texto:</label>
            <input type="text" name="Texto" required />
            <label>Descripción:</label>
            <input type="text" name="Descripcion" required />
            <label>Tipo de respuesta:</label>
            <select name="Tipo" required>
              <option value="S">Selección simple</option>
              <option value="M">Selección múltiple</option>
              <option value="N">Numérica</option>
              <option value="T">Texto simple</option>
              <option value="X">Texto multilínea</option>
            </select>
            <input type="checkbox" name="Obligatoria" />Obligatoria
            <input type="checkbox" name="OrdenInverso" />Orden Inverso
            <label>Unidad:</label>
            <input type="text" name="Unidad"/>
          </div>
        </div>
        <div class="row">
          <div id="OpcionesNumerico" class="twelve columns hide">
            <h3>Opciones</h3>
            <label>Limite inferior:</label>
            <input type="number" name="LimiteInferior" />
            <label>Limite superior:</label>
            <input type="number" name="LimiteSuperior" />
            <label>Paso:</label>
            <input type="number" name="Paso" />
          </div>
          <div id="OpcionesSeleccion" class="twelve columns">
            <div class="barra-botones">
              <a id="agregarOpcion" data-reveal-id="modalAgregarOpcion" title="Agregar opción..."><i class="foundicon-plus"></i></a>
            </div>
            <h3>Opciones</h3>
            <ul class="Opciones"></ul>
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
  
  <div id="HTMLOpcion" class="hide">
    <li class="Opcion">
      <div class="barra-botones">
        <a class="subirOpcion" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarOpcion" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarOpcion" title="Eliminar" href=""><i class="foundicon-remove"></i></a>
      </div>
      <input type="hidden" name="TextoOpcion" value="" />
      <p class="texto"></p>
    </li>
  </div>

  <!-- ventana modal para agregar una opcion -->
  <div id="modalAgregarOpcion" class="reveal-modal medium">
    <h3>Agregar opción</h3>
    <label>Texto:
      <input type="text" name="Texto" />
    </label>
    <div>
      <a class="button agregarOpcion">Agregar</a>
      <a class="close-reveal-modal">&#215;</a>
    </div>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>


  <script>
  
    $('select[name="Tipo"]').change(function(){
      switch($(this).val()){
        case 'S': case 'M':
          $('#OpcionesNumerico').hide('fast');
          $('#OpcionesSeleccion').show('fast');
          break;
        case 'N':
          $('#OpcionesSeleccion').hide('fast');
          $('#OpcionesNumerico').show('fast');
          break;
        default:  
          $('#OpcionesSeleccion').hide('fast');
          $('#OpcionesNumerico').hide('fast');
      }
    });
  
    $('.agregarOpcion').click(function(){
      //busco el contenedor del formulario de agregar opcion
      contFormulario = $('#modalAgregarOpcion');
      
      //leo los datos ingresados
      pTexto = contFormulario.find('input[name="Texto"]').val();
      if (pTexto=='') return;
      
      //tomo la plantilla de la opcion y la agrego al formulario creado
      HTMLOpcion = $('#HTMLOpcion').html();
      $('.Opciones').append(HTMLOpcion);
      nuevaOpcion = $('.Opciones').children().last();
      
      //actualizo valores la plantilla
      nuevaOpcion.find('.texto').html(pTexto);
      nuevaOpcion.find('input[name="TextoOpcion"]').val(pTexto);
      
      //agrego gestor de eventos de los nuevos botones
      nuevaOpcion.find('.subirOpcion').click(function(){
        Contenedor = $(this).parentsUntil('li.Opcion').parent();
        Contenedor.prev().before(Contenedor);
        return false;
      });
      nuevaOpcion.find('.bajarOpcion').click(function(){
        Contenedor = $(this).parentsUntil('li.Opcion').parent();
        Contenedor.next().after(Contenedor);
        return false;
      });
      nuevaOpcion.find('.eliminarOpcion').click(function(){
        Contenedor = $(this).parentsUntil('li.Opcion').parent();
        Contenedor.hide('fast', function(){$(this).remove();});
        return false;
      });
      //ocultar ventana
      $(this).trigger('reveal:close'); //cerrar ventana
    });
    
    $('#Aceptar').click(function(){      
      //por cada opcion creada
      $('.Opciones').children().each(function(i){
        //le cambio los nombres a los campos para poder enviarlos. Se le agrega un numero al final.
        $(this).find('input[name="TextoOpcion"]').attr('name', 'TextoOpcion_'+i);
      });
      $(this).submit();
    });
    
  
  </script>


</body>
</html>