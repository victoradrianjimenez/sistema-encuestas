<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <style>
    .Secciones{
      list-style-type: none;
      margin: 0;
    }
    .Secciones li{
      border: 1px solid #2BA6CB;
      padding: 5px;
      margin: 5px 0;
    }
    .Preguntas{
      list-style-type: none;
      margin: 5px;
    }
    .Preguntas li{
      border: 1px solid #CCCCCC;
    }
    .btn-group{
      float:right;
      line-height:0;
    }
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
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
          <?php $item_submenu = 1;
            include 'templates/submenu-formularios.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Formulario</h4>
          <form action="<?php echo $urlFormulario?>" method="post">
            <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
            <input class="input-block-level" id="campoNombre" type="text" name="nombre" required />
            <label for="campoTitulo">Título: <span class="opcional">*</span></label>
            <input class="input-block-level" id="campoTitulo" type="text" name="titulo" required />
            <div class="row-fluid">
              <div class="span12">
              <div class="span8">
                <label for="campoDescripcion">Descripción: </label>
                <input class="input-block-level" id="campoDescripcion" type="text" name="descripcion" />
              </div>
              <div class="span4">
                <label for="campoAdicionales">Preguntas adicionales: <span class="opcional">*</span></label>
                <input class="input-block-level" id="campoAdicionales" type="number" name="preguntasAdicionales" min="0" max="255" step="1" value="10" />
              </div>
              </div>
            </div>
            <div class="Formularios">
              <legend>Secciones
                <a style="float:right; margin:0 6px;" href="#modalAgregarSeccion" role="button" data-toggle="modal" title="Agregar sección..."><i class="icon-circle-plus"></i></a>
              </legend>
            </div>
            <ul class="Secciones"></ul>
            
            <!-- Botones -->
            <div>
              <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
  
  <div id="HTMLSeccion" class="hide">
    <li class="Seccion">
      <div class="btn-group">
        <a class="subirSeccion" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarSeccion" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarSeccion" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
        <a class="nuevaPregunta" title="Agregar pregunta..." href="#"><i class="icon-circle-plus"></i></a>
      </div>
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
      <div class="btn-group">
        <a class="subirPregunta" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarPregunta" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarPregunta" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
      </div>
      <input type="hidden" name="idPregunta" value="" />
      <p class="texto"></p>
    </li>
  </div>
  
  <!-- ventana modal para agregar una seccion -->
  <div id="modalAgregarSeccion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar sección</h3>
    </div>
    <div class="modal-body">
      <label>Texto: <span class="opcional">*</span></label>
      <input class="input-block-level" type="text" name="textoSeccion" required/>
      <label>Descripción: </label>
      <input class="input-block-level" type="text" name="descripcionSeccion" />
      <label>Tipo de Sección: <span class="opcional">*</span></label>
      <select name="tipoSeccion">
        <option value="N">Normal</option>
        <option value="D">Docente</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarSeccion">Agregar</button>
    </div>
  </div>
  
  <!-- ventana modal para agregar una pregunta -->
  <div id="modalAgregarPregunta" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar pregunta</h3>
    </div>
    <div class="modal-body">
      <label>Buscar pregunta: <span class="opcional">*</span></label>
      <input class="input-block-level" id="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" >
      <input type="hidden" name="idMateria" value=""/>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarPregunta">Agregar</button>
    </div>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script>
    var ContenedorSeccionActual=null;
  
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
      nuevaSeccion.find('.nuevaPregunta').click(function(){
        ContenedorSeccionActual = $(this).parentsUntil('li.Seccion').parent(); //variable global
        $("#modalAgregarPregunta").modal();
        return false;
      });
    });
    
    $('.agregarPregunta').click(function(){
      //busco el contenedor que tiene los datos de la nueva pregunta
      contFormulario = $('#modalAgregarPregunta');
      
      //leo los datos de la seccion
      pidPregunta = $('#modalAgregarPregunta').find('[name="idMateria"]').val();
      ptexto = $('#buscarPregunta').val();
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
    });
    
    //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
    $('#buscarPregunta').keydown(function(event){
      if (event.which==9) return; //ignorar al presionar Tab
      $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
    });
    //realizo la busqueda de usuarios con AJAX
    $('#buscarPregunta').typeahead({
      matcher: function (item) {return true},    
      sorter: function (items) {return items},
      source: function(query, process){
        return $.ajax({
          type: "POST", 
          url: "<?php echo site_url('preguntas/buscarAjax')?>", 
          data:{ buscar: query}
        }).done(function(msg){
          var filas = msg.split("\n");
          var items = new Array();
          for (var i=0; i<filas.length; i++){
            if (filas[i].length<5) continue;
            items.push(filas[i]);
          }
          return process(items);
        });
      },
      highlighter: function (item) {
        var cols = item.split("\t");
        var texto = cols[2]; //texto
        var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
        return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
          return '<strong>' + match + '</strong>'
        })
      },
      updater: function (item) {
        var cols = item.split("\t");
        $('#buscarPregunta').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
        return cols[2];
      }
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