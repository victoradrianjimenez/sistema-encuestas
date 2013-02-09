<!DOCTYPE html>
<!-- Última revisión: 2012-02-04 9:07 p.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar Pregunta</title>
  
  <style>
    .Opciones li{
      border: 1px solid #CCCCCC;
      padding: 5px;
      margin: 10px 0;
    }
    li{
      list-style: none;
    }
    .barra-botones{
      float:right;
      margin: 0;
    }    
    select{margin-bottom: 0;}
    input[type="checkbox"]{margin:10px 0;}
    a.button{width:100%}
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
      <form action="<?php echo site_url('preguntas/nuevo')?>" method="post">
        <div class="row">
          <div class="twelve columns">
            <h3>Pregunta</h3>
            <label for="campoTexto">Texto: <span class="opcional">*</span></label>
            <input id="campoTexto" type="text" name="texto" required />
            <label for="campoDescripcion">Descripción: </label>
            <input id="campoDescripcion" type="text" name="descripcion" required />
            <label for="campoTipo">Tipo de respuesta: <span class="opcional">*</span></label>
            <select id="campoTipo" name="tipo" required>
              <option value="S" selected>Selección simple</option>
              <option value="M">Selección múltiple</option>
              <option value="N">Numérica</option>
              <option value="T">Texto simple</option>
              <option value="X">Texto multilínea</option>
            </select>
            <input type="checkbox" name="obligatoria" /> Obligatoria
            <input type="checkbox" name="ordenInverso" /> Orden Inverso
            <label for="campoUnidad">Unidad: </label>
            <input id="campoUnidad" type="text" name="unidad"/>
          </div>
        </div>
        <div class="row">
          <div id="OpcionesNumerico" class="twelve columns hide">
            <h3>Opciones</h3>
            <label for="campoLimiteInferior">Limite inferior: <span class="opcional">*</span></label>
            <input id="campoLimiteInferior" type="number" name="limiteInferior" />
            <label for="campoLimiteSuperior">Limite superior: <span class="opcional">*</span></label>
            <input id="campoLimiteSuperior" type="number" name="limiteSuperior" />
            <label for="campoPaso">Paso: <span class="opcional">*</span></label>
            <input id="campoPaso" type="number" name="paso" />
          </div>
          <div id="OpcionesSeleccion" class="twelve columns">
            <div class="row">
              <div class="twelve columns">
                <h3 style="float:right"><a id="agregarOpcion" data-reveal-id="modalAgregarOpcion" title="Agregar opción..."><i class="foundicon-plus"></i></a></h3>
                <h3>Opciones</h3>
              </div>
            </div>
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
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <div id="HTMLOpcion" class="hide">
    <li class="Opcion">
      <h3 class="barra-botones">
        <a class="subirOpcion" title="Subir" href=""><i class="foundicon-up-arrow"></i></a>
        <a class="bajarOpcion" title="Bajar" href=""><i class="foundicon-down-arrow"></i></a>
        <a class="eliminarOpcion" title="Eliminar" href=""><i class="foundicon-remove"></i></a>
      </h3>
      <input type="hidden" name="textoOpcion" value="" />
      <p class="texto"></p>
    </li>
  </div>

  <!-- ventana modal para agregar una opcion -->
  <div id="modalAgregarOpcion" class="reveal-modal medium">
    <h3>Agregar opción</h3>
    <label>Texto: <span class="opcional">*</span></label>
    <input type="text" name="texto" />
    <div class="row">
      <div class="four columns centered">
        <a class="button agregarOpcion">Agregar</a>
      </div>
    </div>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>

  <script>
    $('select[name="tipo"]').change(function(){
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
      pTexto = contFormulario.find('input[name="texto"]').val();
      if (pTexto=='') return;
      
      //tomo la plantilla de la opcion y la agrego al formulario creado
      HTMLOpcion = $('#HTMLOpcion').html();
      $('.Opciones').append(HTMLOpcion);
      nuevaOpcion = $('.Opciones').children().last();
      
      //actualizo valores la plantilla
      nuevaOpcion.find('.texto').html(pTexto);
      nuevaOpcion.find('input[name="textoOpcion"]').val(pTexto);
      
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
        $(this).find('input[name="textoOpcion"]').attr('name', 'textoOpcion_'+i);
      });
      $(this).submit();
    });
  </script>
</body>
</html>