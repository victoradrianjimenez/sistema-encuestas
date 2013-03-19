  //selectores de fecha
  var checkin = $('#dpd1').datepicker({
    format: 'dd/mm/yyyy'
  }).on('changeDate', function(ev) {
    if (ev.date.valueOf() > checkout.date.valueOf()) {
      var newDate = new Date(ev.date)
      newDate.setDate(newDate.getDate() + 1);
    }
    else var newDate = checkout.date;
    checkout.setValue(newDate);
    checkin.hide();
    $('#dpd2')[0].focus();
  }).data('datepicker');
  var checkout = $('#dpd2').datepicker({
    format: 'dd/mm/yyyy',
    onRender: function(date) {return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';}
  }).on('changeDate', function(ev) {
    checkout.hide();
  }).data('datepicker');
