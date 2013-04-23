/*
 * Funciones que manejan los eventos de autocompletar, enviando solicitudes AJAX
 */
function autocompletar_usuario(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]+' '+cols[2]+' ('+cols[3]+')'; //nombre, apellido e username
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+' '+cols[2];
    }
  });
}

function autocompletar_departamento(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de departamentos con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]; //nombre
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1];
    }
  });

}

function autocompletar_formulario(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]+' ('+cols[2]+')'; //nombre (fecha)
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+' ('+cols[2]+')';
    }
  });
}

function autocompletar_carrera(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.siblings('i').addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]+" / "+cols[2]; //nombre / plan
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+" / "+cols[2];
    }
  });
}

function autocompletar_materia(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ 
          buscar: query,
          idCarrera: $('input[name="idCarrera"]').val()
        }
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]+" / "+cols[2]; //nombre / codigo
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+" / "+cols[2];
    }
  });
}  

function autocompletar_encuesta(inputObj, url, periodo){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[2]+" / "+cols[3]+' '+periodo; //año / cuatrimestre
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      cont = inputObj.parentsUntil('.control-group').first().parent().removeClass('error');
      cont.find('input[name="idEncuesta"]').val(cols[0]);
      cont.find('input[name="idFormulario"]').val(cols[1]);
      return cols[2]+" / "+cols[3]+' '+periodo;
    }
  });
}

//a diferencia de "autocompletar_encuesta", esta funcion busca solo las encuestas de la materia a la cual se generaron al menos 1 clave
function autocompletar_encuesta_materia(inputObj, url, periodo){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{
          buscar: query,
          idCarrera: $('input[name="idCarrera"]').val(),
          idMateria: $('input[name="idMateria"]').val()
        }
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[2]+" / "+cols[3]+' '+periodo; //año / cuatrimestre
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      cont = inputObj.parentsUntil('.control-group').first().parent().removeClass('error');
      cont.find('input[name="idEncuesta"]').val(cols[0]);
      cont.find('input[name="idFormulario"]').val(cols[1]);
      return cols[2]+" / "+cols[3]+' '+periodo;
    }
  });
}

function autocompletar_pregunta(inputObj, url){
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  inputObj.keydown(function(event){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('.control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  inputObj.typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      inputObj.next().addClass('activo');
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          items.push(filas[i]);
        }
        inputObj.siblings('i').removeClass('activo');
        return process(items);
      });
    },
    highlighter: function (item) {
      var cols = item.split("\t");
      var texto = cols[1]; //texto
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      inputObj.parentsUntil('.control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1];
    }
  });
}

//igual que el anterior, pero en vez de buscar, lista todas las preguntas en una lista desplegable
function completar_pregunta(inputObj, url){
  $.ajax({
    type: "POST", 
    url: url, 
    data:{}
  }).done(function(msg){
    var filas = msg.split("\n");
    for (var i=0; i<filas.length; i++){
      if (filas[i].length<5) continue;
      var cols = filas[i].split("\t");
      var texto = cols[1]; //texto
      inputObj.append('<option value="'+cols[0]+'">'+texto+'</option>');
    }
  });
}