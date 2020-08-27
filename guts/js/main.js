$(document).ready(function() {
  $('.modal_trigger').click(function(event) {
    event.preventDefault();

    // $(this).html("pants");
    var affects = $(this).attr('data-affects');
    alert(affects);
    var current = $(this);
    var date = current.find(".task_date").html();
    var time = current.find(".task_time").html();
    var duration = current.find(".task_duration").html();
    var client = current.find(".task_client").html();
    var project = current.find(".task_project").html();
    var description = current.find(".task_description").html();
    $("#Date").val(date);
    $("#Time").val(time);
    $("#Duration").val(duration);
    $("#Client").val(client);
    $("#Project").val(project);
    $("#Description").val(description);
    $("#Affects").val(affects);
    $("#modal").modal({
      fadeDuration: 50
    });
  });
})