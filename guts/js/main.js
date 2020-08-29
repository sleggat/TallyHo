$(document).ready(function() {
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
    $("#Date").val(date);
    $("#Time").val(time);
    $("#Duration").val(duration);
    $("#Client").val(client);
    $("#Project").val(project);
    $("#Description").val(description);
    $("#Affects").val(affects);
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
    $("#Date").val(date);
    $("#Time").val(time);
    $("#Duration").val(duration);
    $("#Client").val(client);
    $("#Project").val(project);
    $("#Description").val(description);
    $("#Affects").val('');
    $("#modal_update").modal({
      fadeDuration: 50
    });
  });
  //$('.task_container >:not(span)').css("background-color", "rgba(200,1,1,.5)");
  $('.table_col:not(.action_icons)').click(function(event) {
    event.preventDefault();
    $(this).parents(".task_container").toggleClass("highlighted");

    var total_cost = 0;
    $( ".highlighted" ).each(function( index ) {
      var current = $(this).children().find('.task_value').attr("data-costraw");
      total_cost = total_cost + parseFloat(current);
    });

    if (total_cost == 0) { $("#floating_bar").fadeOut('fast'); } else { $("#floating_bar").fadeIn('fast'); }
    $("#total_cost").text(total_cost);
  });
})