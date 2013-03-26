var cantidad_preguntas = 0;
var ContenedorSeccionActual=null;

function personalizar_formulario(cantidadMaximaPreguntas){
  
  $('.agregarPregunta').click(function(){
    if (cantidad_preguntas == cantidadMaximaPreguntas){
      window.alert('Ya superó la cantidad de preguntas adicionales que se pueden agregar al formulario.');
      return;
    }
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
      cantidad_preguntas = cantidad_preguntas-1;
      return false;
    });
    //ocultar ventana
    contFormulario.modal('hide'); //cerrar ventana
    //borro los campos del modal para la proxima
    contFormulario.find('input[name="buscarPregunta"]').val('');   
    contFormulario.find('input[name="idMateria"]').val(''); 
    
    cantidad_preguntas = cantidad_preguntas + 1;
  });
  
  $('.nuevaPregunta').click(function(){
    ContenedorSeccionActual = $(this).parentsUntil('li.Seccion').parent(); //variable global
    $("#modalAgregarPregunta").modal();
    return false;
  });
  
  
  $('#Aceptar').click(function(){      
    //por cada seccion creada
    $('.Secciones').children('.Seccion').each(function(i){
      idSeccion = $(this).find('input[name="idSeccion"]').val();
      //por cada pregunta de la seccion
      $(this).find('.Preguntas').children().each(function(j){
        //le cambio los nombres a los campos para poder enviarlos. Se le agrega un numero al final.
        $(this).find('input[name="idPregunta"]').attr('name', 'idPregunta_'+idSeccion+'_'+j);
      });
    });
    $(this).submit();
  });

}