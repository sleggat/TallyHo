$(document).ready(function() {

  flatpickr('#Date',{ 
  dateFormat: 'Ymd' });

  flatpickr('#Time',{ 
  enableTime: true,
  noCalendar: true,
  dateFormat: "Hi",
  time_24hr: true }); // see https://www.cssscript.com/flat-style-javascript-date-picker-flatpickr/

  $('.modal_update').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var affects = current.attr('data-affects');
    var date = current.attr("data-date");
    var time = current.attr("data-time");
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    $("#modal_header").html("Update Task");
    $("#modal_date").val(date);
    $("#modal_time").val(time);
    $("#modal_duration").val(duration);
    $("#modal_client").val(client);
    $("#modal_project").val(project);
    $("#modal_description").val(description);
    $("#modal_affects").val(affects);
    $("#modal_update").modal({
      fadeDuration: 50
    });
  });

  $('.modal_duplicate').click(function(event) {
    event.preventDefault();
    var dt = new Date();
    var current = $(this).parents(".task_container");
    var date = dt.getFullYear() + ("0"+(dt.getMonth()+1)).slice(-2) + ("0"+dt.getDate()).slice(-2);
    var time = ("00"+dt.getHours()).slice(-2) + ("00"+dt.getMinutes()).slice(-2);
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    $("#modal_header").html("Duplicate Task");
    $("#modal_date").val(date);
    $("#modal_time").val(time);
    $("#modal_duration").val(duration);
    $("#modal_client").val(client);
    $("#modal_project").val(project);
    $("#modal_description").val(description);
    $("#modal_affects").val('');
    $("#modal_update").modal({
      fadeDuration: 50
    });
  });
  //$('.task_container >:not(span)').css("background-color", "rgba(200,1,1,.5)");
  function recalculate_costs() {
    var total_cost = 0;
    $( ".highlighted" ).each(function( index ) {
      var current = $(this).children().find('.task_value').attr("data-costraw");
      total_cost = total_cost + parseFloat(current);
    });
    total_cost = total_cost.toFixed(2);
    $("#total_cost").text(total_cost);
    if (total_cost == 0) { $("#floating_bar").fadeOut('fast'); } else { $("#floating_bar").fadeIn('fast'); }
  }
  $('.new_day').click(function(event) {
    event.preventDefault();
    toggledate = $(this).attr("data-date");
    $('.'+toggledate+" > .task_container").toggleClass("highlighted");
    recalculate_costs();
  });
  $('.table_col:not(.action_icons)').click(function(event) {
    event.preventDefault();
    $(this).parents(".task_container").toggleClass("highlighted");
    recalculate_costs();
  });
  $('#floating_bar').click(function(event) {
    event.preventDefault();
    $('.task_container').removeClass("highlighted");
    recalculate_costs();
  });
})