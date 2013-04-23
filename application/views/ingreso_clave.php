<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ingresar Clave de Acceso - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    div.border-radius{padding: 5px; border: 1px solid #ddd; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;}
  </style>
</head>

<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <h2 class="text-center">Encuesta para mejorar la calidad de la enseñanza</h2>
      <div class="row">
        <div class="span6 offset3 border-radius">
          <form action="<?php echo site_url("claves/ingresar")?>" method="post">
            <fieldset>
              <h5 class="text-center">Ingrese la Clave de Acceso que figura en la tarjeta entregada por el representante de la Comisión Académica</h5>
              <div class="control-group">
                <input class="input-block-level text-center" name="clave" type="text" id="clave" maxlength="16" autocomplete="off" placeholder="Ingrese su clave de acceso" value="<?php echo set_value('clave')?>"/>
                <?php echo form_error('clave')?>
              </div>
              <div class="text-center">
                <input class="btn btn-primary" name="submit" type="submit" id="aceptar" value="   Aceptar   " />
              </div>
            </fieldset>
          </form>
        </div>
      </div>
      <br>
      <div class="row">
        <div id="info" class="span12">
          <p>Le pedimos que invierta unos minutos en leer las instrucciones que se detallan a continuación antes de continuar.</p>
          <p>Esta encuesta está destinada a generar una instancia de comunicación entre alumnos y docentes. Su objetivo principal es que los profesores conozcan la opinión que tienen sus alumnos sobre su trabajo como docente, de manera sistemática, para poder incorporar mejoras teniendo en cuenta el punto de vista de los propios estudiantes, y también conocer las áreas que requieren mayor impulso y desarrollo en el trabajo docente, para orientar los esfuerzos de la institución hacia ellas.</p>
          <p>La encuesta tiene carácter <strong>anónimo</strong>, <strong>no es obligatoria</strong> y se realiza en la mayoría de las asignaturas de la <?php echo NOMBRE_FACULTAD?>.</p>
          <p>Para poder completar la encuesta, usted deberá ingresar una clave única impresa que le fue entregada. Cada clave es válida solamente para la asignatura que se indica en la misma, y en caso de estar cursando varias asignaturas, deberá recibir igual número de claves. A su vez, cada clave permite completar y enviar el formulario una sola vez. Una vez que la encuesta fue enviada, la clave quedará inhabilitada para posteriores accesos.</p>
          <p>En cada pregunta de la encuesta, se detallan sus posibles respuestas. Si no desea opinar sobre un aspecto en particular deberá dejar esa pregunta con el valor NC (No Contesta). Además, se proporcionan campos de texto para que ingrese sus opiniones libremente.</p>
          <p>Su participación es importante para que, en conjunto, alumnos y docentes podamos mejorar la calidad del proceso de enseñanza-aprendizaje. Por eso, si bien esta encuesta no es obligatoria, le solicitamos que se tome el tiempo necesario para llenarla con responsabilidad. Le sugerimos que, previa lectura completa del cuestionario, sírvase contestarlo de acuerdo a lo que cree y siente, y que antes de enviar la encuesta, revise la misma constatando que está completa y correcta.</p>
          <p>Su aporte claro y sincero ayudará a mejorar nuestras Carreras.</p>
          <p>Muchas gracias.</p>      
        </div>
      </div>
    </div>
    <div id="push"></div>
  </div>
  <?php include 'templates/footer.php'?>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
</body>
</html>
