/*
 * Manejar eventos de controles de edicion de preguntas 
 */
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