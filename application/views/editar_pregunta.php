<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar Pregunta</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <style>
    .Opciones{
      margin:0;
    }
    .Opciones li{
      border: 1px solid #CCCCCC;
      padding: 5px;
      margin: 5px 0;
    }
    li{
      list-style: none;
    }
    .btn-group{
      float:right;
      line-height:0;
    }
  </style>
</head>
<body>
  <?php include 'templates/menu-nav.php'?>
  <div id="wrapper" class="container">
    <div class="row">
      <!-- Titulo -->
      <div class="span12">
        <h3>Gestión de Formularios</h3>
        <p>---Descripción---</p>
      </div>
    </div>
    
    <div class="row">
      <!-- SideBar -->
      <div class="span3" id="menu">
        <h4>Navegación</h4>
        <ul class="nav nav-pills nav-stacked">      
          <li><a href="<?php echo site_url("formularios")?>">Formularios</a></li>
          <li class="active"><a href="<?php echo site_url("preguntas")?>">Preguntas</a></li>
        </ul>
      </div>
      
      <!-- Main -->
      <div class="span9">
        <h4>Preguntas</h4>
        <form action="<?php echo site_url('preguntas/nueva')?>" method="post">
          <label for="campoTexto">Texto: <span class="opcional">*</span></label>
          <input class="input-block-level" id="campoTexto" type="text" name="texto" required />
          <label for="campoDescripcion">Descripción: </label>
          <input class="input-block-level" id="campoDescripcion" type="text" name="descripcion" />
          <label for="campoTipo">Tipo de respuesta: <span class="opcional">*</span></label>
          <select id="campoTipo" name="tipo" required>
            <option value="S" selected>Selección simple</option>
            <option value="M">Selección múltiple</option>
            <option value="N">Numérica</option>
            <option value="T">Texto simple</option>
            <option value="X">Texto multilínea</option>
          </select>
          <label class="checkbox">
            <input type="checkbox" name="obligatoria" /> Obligatoria
          </label>
          <label class="checkbox">
            <input type="checkbox" name="ordenInverso" /> Orden Inverso
          </label>
          <label for="campoUnidad">Unidad: </label>
          <input id="campoUnidad" type="text" name="unidad"/>
        
          <div id="OpcionesNumerico" class="hide">
            <h3>Opciones</h3>
            <label for="campoLimiteInferior">Limite inferior: <span class="opcional">*</span></label>
            <input id="campoLimiteInferior" type="number" name="limiteInferior" />
            <label for="campoLimiteSuperior">Limite superior: <span class="opcional">*</span></label>
            <input id="campoLimiteSuperior" type="number" name="limiteSuperior" />
            <label for="campoPaso">Paso: <span class="opcional">*</span></label>
            <input id="campoPaso" type="number" name="paso" />
          </div>
          
          <div id="OpcionesSeleccion">
            <legend>Opciones<a style="float:right; margin:0 6px;" href="#modalAgregarOpcion" role="button" data-toggle="modal" title="Agregar opción..."><i class="icon-circle-plus"></i></a></legend>
            <div class="row">
              <div class="span9">
                <ul class="Opciones"></ul>
              </div>
            </div>
          </div>
          
          <!-- Botones -->
          <div>
            <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
          </div>
        </form>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
  
  
  <div id="HTMLOpcion" class="hide">
    <li class="Opcion">
      <div class="btn-group">
        <a class="subirOpcion" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarOpcion" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarOpcion" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
      </div>
      <p class="texto"></p>
      <input type="hidden" name="textoOpcion" value="" />
    </li>
  </div>

  <!-- ventana modal para agregar una opcion -->
  <div id="modalAgregarOpcion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar opción</h3>
    </div>
    <div class="modal-body">
      <label>Texto: <span class="opcional">*</span>
        <input type="text" name="texto" required />
      </label>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarOpcion">Agregar</button>
    </div>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
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