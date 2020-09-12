$(document).ready(function() {
    initpicker('#form_add');
    $("#add_client").easyAutocomplete(client_options);
    $("#add_project").easyAutocomplete(project_options);
})
$('.toggle_filters .icon_svg').click(function(event) {
    event.preventDefault();
    $('.filters_form').toggle();
});

$('.modal_update').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var path = current.attr('data-path');
    var datetime = current.attr("data-datetime");
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    $("#modal_update .modal_header").html("Update Task");
    $("#modal_update .modal_datetime").val(datetime);
    $("#modal_update .modal_duration").val(duration);
    $("#modal_update .modal_client").val(client);
    $("#modal_update .modal_project").val(project);
    $("#modal_update .modal_description").val(description);
    $("#modal_update .modal_path").val(path);
    $("#modal_update").modal({
        fadeDuration: 300,
        fadeDelay: 0.5
    });
    initpicker('#modal_update');
});

$('.modal_duplicate').click(function(event) {
    event.preventDefault();
    var dt = new Date();
    var current = $(this).parents(".task_container");
    var datetime = dt.getFullYear() + ("0" + (dt.getMonth() + 1)).slice(-2) + ("0" + dt.getDate()).slice(-2) + ("00" + dt.getHours()).slice(-2) + ("00" + dt.getMinutes()).slice(-2);
    // it's quite lucky daterangepicker converts this from 2300 to 11:00 PM
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    $("#modal_update .modal_header").html("Duplicate Task");
    $("#modal_update .modal_datetime").val(datetime);
    $("#modal_update .modal_duration").val(duration);
    $("#modal_update .modal_client").val(client);
    $("#modal_update .modal_project").val(project);
    $("#modal_update .modal_description").val(description);
    $("#modal_update .modal_path").val('');
    $("#modal_update").modal({
        fadeDuration: 300,
        fadeDelay: 0.5
    });
    initpicker('#modal_update');
});

$('.modal_delete').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var path = current.attr('data-path');
    $("#modal_delete .modal_header").html("Delete Task");
    $("#modal_delete .modal_path").val(path);
    $("#modal_delete .modal_delete_path").html(path);
    $("#modal_delete").modal({
        fadeDuration: 300,
        fadeDelay: 0.5
    });
});

function initpicker(element) {
    $(element+' .modal_datetime').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "timePickerIncrement": 5,
        "locale": {
            "format": "YYYY-MM-DD hh:mm A",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "daysOfWeek": [
                "Su",
                "Mo",
                "Tu",
                "We",
                "Th",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ]
        },
        "autoUpdateInput": true
    });
}
function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}
//$('.task_container >:not(span)').css("background-color", "rgba(200,1,1,.5)");
function recalculate_costs() {
    var total_cost = 0;
    $(".highlighted").each(function(index) {
        var current = $(this).children().find('.task_value').attr("data-costraw");
        total_cost = total_cost + parseFloat(current);
    });
    total_cost = total_cost.toFixed(2);
    $("#total_cost").text(total_cost);
    if (total_cost == 0) {
        $("#floating_bar").fadeOut('fast');
    } else {
        $("#floating_bar").fadeIn('fast');
    }
}
$('.day_header').click(function(event) {
    event.preventDefault();
    toggledate = $(this).attr("data-date");
    $('.' + toggledate + " > .task_container").toggleClass("highlighted");
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
