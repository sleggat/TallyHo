$(document).ready(function() {
    $(document).keyup(function(e) {
      if (e.keyCode === 27) $(".modal").removeClass("is-active");   // esc
    });
    $(".delete, .modal-background").click(function() {
       $(".modal").removeClass("is-active");
    });
    initpicker('#form_add');
    $(".modal_client").easyAutocomplete(client_options);
    $(".modal_project").easyAutocomplete(project_options);
    $("#filter_client").easyAutocomplete(client_options);
    $("#filter_project").easyAutocomplete(project_options);
})

$('.modal_edit').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var path = current.attr('data-path');
    var datetime = current.attr("data-datetime");
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    task_handler('edit', client, project, description, datetime, duration, path);
});

$('.modal_duplicate').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var duration = current.attr("data-duration");
    var client = current.attr("data-client");
    var project = current.attr("data-project");
    var description = current.attr("data-description");
    task_handler('duplicate', client, project, description, null, duration, null);
});

$('.modal_delete').click(function(event) {
    event.preventDefault();
    var current = $(this).parents(".task_container");
    var path = current.attr('data-path');
    $("#modal_delete .modal-card-title").html("<span class=\"icon is-size-6\"><i class=\"fas fa-trash\"></i></span> Delete Task");
    $("#modal_delete .modal_path").val(path);
    $("#modal_delete .modal_delete_path").html(path);
    $("#modal_delete").addClass("is-active");
});


/* Functions */

function task_handler(type, client, project, description, datetime, duration, path) {
    // will combine all add/duplicate/restart/edit

    event.preventDefault();
    autofocus = 'client';
    duration = parseFloat(duration) ? duration : 15;
    switch (type) {
        case 'continue':
            header = "<span class=\"icon is-size-6\"><i class=\"far fa-clone\"></i></span> Continue Task";
            autofocus = 'description';
            break;
        case 'duplicate':
            header = "<span class=\"icon is-size-6\"><i class=\"fas fa-copy\"></i></span> Duplicate Task";
            datetime = get_current_datetime();
            // it's quite lucky daterangepicker converts this from 2300 to 11:00 PM
            break;
        case 'add':
            header = "<span class=\"icon is-size-6\"><i class=\"fas fa-plus\"></i></span> Add Task";
            datetime = get_current_datetime();
            break;
        case 'edit':
            header = "<span class=\"icon is-size-6\"><i class=\"fas fa-edit\"></i></span> Edit Task";
            break;
    }
    $("#modal_update .modal-card-title").html(header);
    $("#modal_update .modal_datetime").val(datetime);
    $("#modal_update .modal_duration").val(duration);
    $("#modal_update .modal_client").val(client);
    $("#modal_update .modal_project").val(project);
    $("#modal_update .modal_description").val(description);
    $("#modal_update .modal_path").val(path);
    $("#modal_update .modal_"+autofocus).attr("autofocus","autofocus").focus(); // doesnt seem that reliable
    $("#modal_update").addClass("is-active");
    initpicker('#modal_update');
}
function get_current_datetime() {
    // returns date in this format: YYYYMMDDHHMM e.g 202010011616
    var dt = new Date();
    var datetime = dt.getFullYear() + ("0" + (dt.getMonth() + 1)).slice(-2) + ("0" + dt.getDate()).slice(-2) + ("00" + dt.getHours()).slice(-2) + ("00" + dt.getMinutes()).slice(-2);
    return datetime
}
function initpicker(element) {
    $(element+' .modal_datetime').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "timePickerIncrement": 1,
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

function timeConvert(n) {
    var num = n;
    var hours = (num / 60);
    var rhours = Math.floor(hours);
    var minutes = (hours - rhours) * 60;
    var rminutes = Math.round(minutes);
    return rhours + "h " + rminutes + "m";
}

function recalculate_costs() {
    var total_cost = 0;
    var total_mins = 0;
    var total_selected =0;
    $(".highlighted").each(function(index) {
        var costraw = $(this).children().find('.task_value').attr("data-costraw");
        var mins = $(this).children().find('.task_duration').text();
        total_cost = total_cost + parseFloat(costraw);
        total_mins = total_mins + parseFloat(mins);
        total_selected = index + 1;
    });
    total_cost = total_cost.toFixed(2);
    $("#total_selected").text(total_selected);
    $("#total_mins").text(timeConvert(total_mins));
    $("#total_cost").text(total_cost);
    if (total_cost == 0) {
        $("#tally").fadeOut('fast');
    } else {
        $("#tally").fadeIn('fast');
    }
}
$('.day_header').click(function(event) {
    event.preventDefault();
    toggledate = $(this).attr("data-date");
    $('.' + toggledate + " > .task_container").toggleClass("highlighted");
    recalculate_costs();
});
$('.table_col:not(.task_col_4)').click(function(event) {
    event.preventDefault();
    $(this).parents(".task_container").toggleClass("highlighted");
    recalculate_costs();
});
$('#floating_bar').click(function(event) {
    event.preventDefault();
    $('.task_container').removeClass("highlighted");
    recalculate_costs();
});
