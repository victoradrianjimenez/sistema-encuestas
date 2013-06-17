
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
  
  //ocultar ventana
  contFormulario.modal('hide'); //cerrar ventana
  //borro los campos del modal para la proxima
  contFormulario.find('input[name="textoSeccion"]').val('');   
  contFormulario.find('input[name="descripcionSeccion"]').val('');  
});

$('.agregarPregunta').click(function(){
  //busco el contenedor que tiene los datos de la nueva pregunta
  contFormulario = $('#modalAgregarPregunta');
  
  //leo los datos de la seccion
  pidPregunta = $('#modalAgregarPregunta').find('[name="idPregunta"]').val();
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
  //ocultar ventana
  contFormulario.modal('hide'); //cerrar ventana
  //borro los campos del modal para la proxima
  contFormulario.find('input[name="buscarPregunta"]').val('');   
  contFormulario.find('input[name="idMateria"]').val('');  
});

$('.nuevaPregunta').click(function(){
  ContenedorSeccionActual = $(this).parentsUntil('li.Seccion').parent(); //variable global
  $("#modalAgregarPregunta").modal();
  return false;
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