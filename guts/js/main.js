$(document).ready(function() {

    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        $(".modal").removeClass("is-active");   // esc
        $("html").removeClass('is-clipped');
      }
    })
    $(".delete, .modal-background").click(function() {
       $(".modal").removeClass("is-active");
       $("html").removeClass('is-clipped');
    })
    initpicker('#form_add');

    $(".modal_edit").bind("click", edit_task);
    $(".table_col").bind("dblclick", edit_task);
    $('.modal_duplicate').bind("click", duplicate_task);
    $('.modal_delete').bind("click", delete_task);

    $('.dropdown-trigger').on('click',function(){
        event.preventDefault();
        var dropdown = $(this).parents('.dropdown');
        var width = $(this).parents('.dropdown-container').width();
        $(this).parents('.dropdown-container').find('.dropdown-content').width(width);
        $(".dropdown").not(dropdown).removeClass('is-active');
        dropdown.toggleClass('is-active');
    })
    $('.dropdown-selection').on('click',function(){
        event.preventDefault();
        var value = $(this).attr("data-value");
        var current = $(this).parents(".dropdown-container").find(".input");
        $(current).val(value);
        $(".dropdown").removeClass('is-active');
    })

    $('.set_duration').on('click',function(){
        event.preventDefault();
        $('.set_duration').removeClass('is-link').addClass('is-white');
        $('.set_expense').removeClass('is-white').addClass('is-link');
        $('.modal_tasktype').val('duration');
        if ($('.field_duration').is(":hidden")) {
            $('.field_expense').hide();
            $('.field_duration').show();
        }
    });
    $('.set_expense').on('click',function(){
        event.preventDefault();
        $('.set_expense').removeClass('is-link').addClass('is-white');
        $('.set_duration').removeClass('is-white').addClass('is-link');
        $('.modal_tasktype').val('expense');
        if ($('.field_expense').is(":hidden")) {
            $('.field_duration').hide();
            $('.field_expense').show();
        }
    })

    $('.day_header').on('click',function(){
        event.preventDefault();
        var toggledate = $(this).attr("data-date");
        $('.' + toggledate + " > .task_container").toggleClass("highlighted");
        recalculate_costs();
    })
    $('.table_col:not(.task_col_4)').on('click',function(){
        event.preventDefault();
        $(this).parents(".task_container").toggleClass("highlighted");
        recalculate_costs();
    })
    $('#tally').on('click',function(){
        event.preventDefault();
        $('.task_container').removeClass("highlighted");
        recalculate_costs();
    })
})

/* Functions */

function validateForm() {

    var form = $('form[name="main_form"]'),
        validates = true;

    // strip out '.' from client and project as they cause problems when creating folders
    $(form).find('input[name="Client"]').val($(form).find('input[name="Client"]').val().replaceAll(".",""));
    $(form).find('input[name="Project"]').val($(form).find('input[name="Project"]').val().replaceAll(".",""));

    if ($(form).find('input[name="Client"]').val() == "") {
        display_error('Client', 'Invalid Client name');
    }
    if ($(form).find('input[name="Project"]').val() == "") {
        display_error('Project', 'Invalid Project name');
    }
    if ($(form).find('input[name="DateTime"]').val() == "") {
        display_error('DateTime', 'Invalid date/time');
    }
    if ($(form).find('input[name="TaskType"]').val() == "duration") {
        if (isNaN($(form).find('input[name="Duration"]').val()) || $(form).find('input[name="Duration"]').val() < 1 ) {
            display_error('Duration', 'Invalid Duration');
        }
    }
    if ($(form).find('input[name="TaskType"]').val() == "expense") {
        if (isNaN($(form).find('input[name="Expense"]').val()) || $(form).find('input[name="Expense"]').val() < 1 ) {
            display_error('Expense', 'Invalid Expense');
        }
    }
    function display_error(field, error) {
        validates = false;
        $(form).find('.form_error').html('<p>' + error + '</p>').removeClass("is-hidden");
        $(form).find('input[name="' + field + '"]').focus();
    }
    if (validates == false ) { return false; }
}

function edit_task() {
    event.preventDefault();
    var current = $(this).parents(".task_container"),
        path = current.attr('data-path'),
        datetime = current.attr("data-datetime"),
        duration = current.attr("data-duration"),
        expense = current.attr("data-expense"),
        client = current.attr("data-client"),
        project = current.attr("data-project"),
        description = current.attr("data-description");
    task_handler('edit', client, project, description, datetime, duration, expense, path);
}
function duplicate_task() {
    event.preventDefault();
    var current = $(this).parents(".task_container"),
        duration = current.attr("data-duration"),
        expense = current.attr("data-expense"),
        client = current.attr("data-client"),
        project = current.attr("data-project"),
        description = current.attr("data-description");
    task_handler('duplicate', client, project, description, null, duration, expense, null);
}
function delete_task() {
    event.preventDefault();
    var current = $(this).parents(".task_container"),
        path = current.attr('data-path');
    $("#modal_delete .modal-card-title").html("<span class=\"icon is-size-6\"><i class=\"fas fa-trash\"></i></span> Delete Task");
    $("#modal_delete .modal_path").val(path);
    $("#modal_delete .modal_delete_path").html(path);
    $("#modal_delete").addClass("is-active");
}

function task_handler(type, client, project, description, datetime, duration, expense, path) {
    // currently handles add /restart (via onClick), duplicate, and edit

    event.preventDefault();
    $(".dropdown").removeClass('is-active');
    var autofocus = 'client',
        duration = parseFloat(duration) ? duration : 15;
    switch (type) {
        case 'continue':
            var header = "<span class=\"icon is-size-6\"><i class=\"far fa-clone\"></i></span> Continue Task";
            var datetime = get_current_datetime();
            var autofocus = 'description';
            break;
        case 'duplicate':
            var header = "<span class=\"icon is-size-6\"><i class=\"fas fa-copy\"></i></span> Duplicate Task";
            var datetime = get_current_datetime();
            var autofocus = 'description';
            break;
        case 'add':
            var header = "<span class=\"icon is-size-6\"><i class=\"fas fa-plus\"></i></span> Add New Task";
            var datetime = get_current_datetime();
            var autofocus = 'client';
            break;
        case 'edit':
            var header = "<span class=\"icon is-size-6\"><i class=\"fas fa-edit\"></i></span> Edit Task";
            var autofocus = 'description';
            break;
    }
    $("#modal_update .modal-card-title").html(header);
    $("#modal_update .modal_datetime").val(datetime);
    $("#modal_update .modal_duration").val(duration);
    $("#modal_update .modal_expense").val(expense);
    $("#modal_update .modal_client").val(client);
    $("#modal_update .modal_project").val(project);
    $("#modal_update .modal_description").val(description);
    $("#modal_update .modal_path").val(path);
    $("#modal_update .modal_"+autofocus).attr("autofocus","autofocus").focus(); // doesnt seem that reliable
    $("#modal_update").addClass("is-active");
    $("html").addClass('is-clipped');
    if (expense > 0) {
        $('.set_expense').trigger( "click" );
    }
    else {
        $('.set_duration').trigger( "click" );
    }
    initpicker('#modal_update');
}

function get_current_datetime() {
    // returns date in this format: YYYYMMDDHHMM e.g 202010011616
    var dt = new Date(),
        datetime = dt.getFullYear() + ("0" + (dt.getMonth() + 1)).slice(-2) + ("0" + dt.getDate()).slice(-2) + ("00" + dt.getHours()).slice(-2) + ("00" + dt.getMinutes()).slice(-2);
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


function timeConvert(n) {
    var num = n,
        hours = (num / 60),
        rhours = Math.floor(hours),
        minutes = (hours - rhours) * 60,
        rminutes = Math.round(minutes);
    return rhours + "h " + rminutes + "m";
}

function recalculate_costs() {
    var total_cost = 0,
        total_mins = 0,
        total_selected =0;
    $(".highlighted").each(function(index) {
        var costraw = $(this).children().find('.task_value').attr("data-costraw"),
            mins = $(this).children().find('.task_duration').text();
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


