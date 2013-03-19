//ocultar mensaje de error al escribir
$('input[type="text"], input[type="number"], input[type="password"]').keyup(function(){
  $(this).siblings('span.label').hide('fast');
});
