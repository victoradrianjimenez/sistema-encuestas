<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ingresar Clave de Acceso</title>
  
  <style>
    input[name="submit"]{width:100%;}
    input[name="clave"]{text-align:center;}
    form h6{text-align:center;}
    #info p{font-size: 11pt; text-align:justify;}
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
    <div class="six columns centered">  
      <form action="<?php echo site_url("claves/ingresar")?>" method="post">
        <fieldset>
          <div class="twelve columns">
            <h6>Ingrese la Clave de Acceso que figura en la tarjeta entregada por el representante de la Comisión Académica</h6>
            <input class="<?php echo ($mensaje!='')?'error':''?>" name="clave" type="text" id="clave" maxlength="16" placeholder="Ingrese su clave de acceso" value="<?php echo $clave?>"/>
            <small class="<?php echo ($mensaje!='')?'error':'hide'?>"><?php echo $mensaje?></small>           
          </div>
          <div class="four mobile-two columns centered pull-one-mobile pull-four">
            <input class="button" name="submit" type="submit" id="aceptar" value="Aceptar" />
          </div>
        </fieldset>
      </form>
    </div>
    <div id="info" class="twelve columns">
      <p>Le pedimos que invierta unos minutos en leer las instrucciones que se detallan a continuación antes de continuar.</p>
      <p>Esta encuesta está destinada a generar una instancia de comunicación entre alumnos y docentes. Su objetivo principal es que los profesores conozcan la opinión que tienen sus alumnos sobre su trabajo como docente, de manera sistemática, para poder incorporar mejoras teniendo en cuenta el punto de vista de los propios estudiantes, y también conocer las áreas que requieren mayor impulso y desarrollo en el trabajo docente, para orientar los esfuerzos de la institución hacia ellas.</p>
      <p>La encuesta tiene carácter <strong>anónimo</strong>, <strong>no es obligatoria</strong> y se realiza en todas las asignaturas de las tres Carreras dependientes del Departamento de Electricidad, Electrónica y Computación.</p>
      <p>Estará disponible sólo desde el lunes 12/11/2012 hasta el viernes 07/12/2012, para permitir una realimentación rápida entre docentes y alumnos.</p>
      <p>Para poder completar la encuesta, usted deberá ingresar una clave única impresa que le fue entregada. Cada clave es válida solamente para la asignatura que se indica en la misma, y en caso de estar cursando varias asignaturas, deberá recibir igual número de claves. A su vez, cada clave permite completar y enviar el formulario una sola vez. Una vez que la encuesta fue enviada, la clave quedará inhabilitada para posteriores accesos.</p>
      <p>En cada pregunta de la encuesta, se detallan sus posibles respuestas. Si no desea opinar sobre un aspecto en particular deberá dejar esa pregunta con el valor NC (No Contesta). Además, se proporcionan campos de texto para que ingrese sus opiniones libremente.</p>
      <p>Su participación es importante para que, en conjunto, alumnos y docentes podamos mejorar la calidad del proceso de enseñanza-aprendizaje. Por eso, si bien esta encuesta no es obligatoria, le solicitamos que se tome el tiempo necesario para llenarla con responsabilidad. Le sugerimos que, previa lectura completa del cuestionario, sírvase contestarlo de acuerdo a lo que cree y siente, y que antes de enviar la encuesta, revise la misma constatando que está completa y correcta.</p>
      <p>Su aporte claro y sincero ayudará a mejorar nuestras Carreras.</p>
      <p>Muchas gracias.</p>      
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
  
  <script type="text/javascript">
    //esconder los mensajes de error al establecer foco
    $('input[name="clave"]').focus(function(){
      $('small.error').hide();
      $('.error').removeClass('error');
    });
  </script>
</body>
</html>