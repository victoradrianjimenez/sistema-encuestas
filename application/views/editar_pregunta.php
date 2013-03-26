<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <style>
    .Opciones {margin:0;}
    .Opciones li{border: 1px solid #CCCCCC; padding: 5px; margin: 5px 0;}
    li.Opcion {list-style: none;}
    li.Opcion .btn-group {float:right; line-height:0;}
    #botonAgregarOpcion {float:right; margin:0 6px;}
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
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los formularios utilizados para la toma de encuestas.</p>
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
            <input class="input-block-level" id="campoTexto" type="text" name="texto" maxlength="250" value="<?php echo $pregunta->texto?>" />
            <?php echo form_error('texto')?>
            
            <label for="campoDescripcion">Descripción: </label>
            <input class="input-block-level" id="campoDescripcion" type="text" name="descripcion" maxlength="250" value="<?php echo $pregunta->descripcion?>" />
            <?php echo form_error('descripcion')?>

            <?php if (!isset($disabled) || !$disabled):?>
              
              <label for="campoTipo">Tipo de respuesta: <span class="opcional">*</span></label>
              <select id="campoTipo" name="tipo" required>
                <option value="<?php echo TIPO_SELECCION_SIMPLE?>" <?php echo set_select('tipo', TIPO_SELECCION_SIMPLE, TRUE)?>>Selección simple</option>
                <option value="<?php echo TIPO_NUMERICA?>" <?php echo set_select('tipo', TIPO_NUMERICA)?>>Numérica</option>
                <option value="<?php echo TIPO_TEXTO_SIMPLE?>" <?php echo set_select('tipo', TIPO_TEXTO_SIMPLE)?>>Texto simple</option>
                <option value="<?php echo TIPO_TEXTO_MULTILINEA?>" <?php echo set_select('tipo', TIPO_TEXTO_MULTILINEA)?>>Texto multilínea</option>
              </select>
              <?php echo form_error('tipo')?>
              
              <label class="checkbox">
                <input type="checkbox" name="ordenInverso" value="1" <?php echo ($pregunta->modoIndice==MODO_INDICE_INVERSO)?'checked="checked"':''?> /> Orden Inverso
              </label>
              <label class="checkbox">
                <input type="checkbox" name="indiceNulo" value="1" <?php echo ($pregunta->modoIndice==MODO_INDICE_NULO)?'checked="checked"':''?> /> La pregunta no influye en el cálculo del índice
              </label>
              
              <label for="campoUnidad">Unidad: </label>
              <input id="campoUnidad" type="text" name="unidad" maxlength="10" value="<?php echo $pregunta->unidad?>"/>
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
                <legend>Opciones<a id="botonAgregarOpcion" href="#modalAgregarOpcion" role="button" data-toggle="modal" title="Agregar opción..."><i class="icon-circle-plus"></i></a></legend>
                <div class="row">
                  <div class="span9">
                    <ul class="Opciones">
                      <?php foreach ($opciones as $opcion) {
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
      <label class="control-label" for="campoTextoOpcion">Texto: 
        <input id="campoTextoOpcion" type="text" name="texto" maxlength="40" required />
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
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script src="<?php echo base_url('js/preguntas.js')?>"></script>
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
  </script>
</body>
</html>