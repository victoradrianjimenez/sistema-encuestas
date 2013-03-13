<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
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
          <?php $item_submenu = 2;
            include 'templates/submenu-formularios.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Preguntas</h4>
          <form action="<?php echo $urlFormulario?>" method="post">
            <input type="hidden" name="idPregunta" value="<?php echo $pregunta->idPregunta?>"/>
            
            <label for="campoTexto">Texto: <span class="opcional">*</span></label>
            <input class="input-block-level" id="campoTexto" type="text" name="texto" value="<?php echo $pregunta->texto?>" />
            <?php echo form_error('texto')?>
            
            <label for="campoDescripcion">Descripción: </label>
            <input class="input-block-level" id="campoDescripcion" type="text" name="descripcion" value="<?php echo $pregunta->descripcion?>" />
            <?php echo form_error('descripcion')?>

            <?php if (!isset($disabled)):?>
              
              <label for="campoTipo">Tipo de respuesta: <span class="opcional">*</span></label>
              <select id="campoTipo" name="tipo" required>
                <option value="S" <?php echo set_select('tipo', 'S', TRUE)?>>Selección simple</option>
                <option value="N" <?php echo set_select('tipo', 'N')?>>Numérica</option>
                <option value="T" <?php echo set_select('tipo', 'T')?>>Texto simple</option>
                <option value="X" <?php echo set_select('tipo', 'X')?>>Texto multilínea</option>
              </select>
              <?php echo form_error('tipo')?>
              
              <label class="checkbox">
                <input type="checkbox" name="ordenInverso" <?php echo ($pregunta->ordenInverso=='S')?'checked="checked"':''?> /> Orden Inverso
              </label>
              
              <label for="campoUnidad">Unidad: </label>
              <input id="campoUnidad" type="text" name="unidad" value="<?php echo $pregunta->unidad?>"/>
              <?php echo form_error('unidad')?>
  
              <div id="OpcionesNumerico" class="hide">
                <h4>Opciones</h4>
                <label for="campoLimiteInferior">Limite inferior: <span class="opcional">*</span></label>
                <input id="campoLimiteInferior" type="number" name="limiteInferior" value="<?php echo $pregunta->limiteInferior?>" />
                <?php echo form_error('limiteInferior')?>
                
                <label for="campoLimiteSuperior">Limite superior: <span class="opcional">*</span></label>
                <input id="campoLimiteSuperior" type="number" name="limiteSuperior" value="<?php echo $pregunta->limiteSuperior?>" />
                <?php echo form_error('limiteSuperior')?>
                
                <label for="campoPaso">Paso: <span class="opcional">*</span></label>
                <input id="campoPaso" type="number" name="paso" value="<?php echo $pregunta->paso?>" />
                <?php echo form_error('paso')?>
              </div>
              
              <div id="OpcionesSeleccion">
                <legend>Opciones<a style="float:right; margin:0 6px;" href="#modalAgregarOpcion" role="button" data-toggle="modal" title="Agregar opción..."><i class="icon-circle-plus"></i></a></legend>
                <div class="row">
                  <div class="span9">
                    <ul class="Opciones">
                      <?php
                        foreach ($opciones as $opcion) {
                          echo '
                          <li class="Opcion">
                            <div class="btn-group">
                              <a class="subirOpcion" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
                              <a class="bajarOpcion" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
                              <a class="eliminarOpcion" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
                            </div>
                            <p class="texto">'.$opcion.'</p>
                            <input type="hidden" name="textoOpcion[]" value="'.$opcion.'" />
                          </li>';  
                        }
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              
            <?php endif?>
            
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
  
  <div id="HTMLOpcion" class="hide">
    <li class="Opcion">
      <div class="btn-group">
        <a class="subirOpcion" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarOpcion" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarOpcion" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
      </div>
      <p class="texto"></p>
      <input type="hidden" name="textoOpcion[]" value="" />
    </li>
  </div>

  <!-- ventana modal para agregar una opcion -->
  <div id="modalAgregarOpcion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar opción</h3>
    </div>
    <div class="modal-body">
      <label>Texto:
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script>
    $('select[name="tipo"]').change(function(){
      switch($(this).val()){
      case "<?php echo TIPO_SELECCION_SIMPLE?>":
        $('#OpcionesNumerico').hide('fast');
        $('#OpcionesSeleccion').show('fast');
        break;
      case "<?php echo TIPO_NUMERICA?>":
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
      nuevaOpcion.find('input[name="textoOpcion[]"]').val(pTexto);
      
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
      $('#modalAgregarOpcion').modal('hide'); //cerrar ventana
      //borro los campos del modal para la proxima
      contFormulario.find('input[name="texto"]').val('');       
    });
  </script>
</body>
</html>