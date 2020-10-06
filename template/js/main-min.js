function task_handler(e,t,a,i,s,d,l,n){switch(event.preventDefault(),$(".dropdown").removeClass("is-active"),autofocus="client",d=parseFloat(d)?d:15,e){case"continue":header='<span class="icon is-size-6"><i class="far fa-clone"></i></span> Continue Task',s=get_current_datetime(),autofocus="description";break;case"duplicate":header='<span class="icon is-size-6"><i class="fas fa-copy"></i></span> Duplicate Task',s=get_current_datetime(),autofocus="description";break;case"add":header='<span class="icon is-size-6"><i class="fas fa-plus"></i></span> Add New Task',s=get_current_datetime(),autofocus="client";break;case"edit":header='<span class="icon is-size-6"><i class="fas fa-edit"></i></span> Edit Task',autofocus="description"}$("#modal_update .modal-card-title").html(header),$("#modal_update .modal_datetime").val(s),$("#modal_update .modal_duration").val(d),$("#modal_update .modal_expense").val(l),$("#modal_update .modal_client").val(t),$("#modal_update .modal_project").val(a),$("#modal_update .modal_description").val(i),$("#modal_update .modal_path").val(n),$("#modal_update .modal_"+autofocus).attr("autofocus","autofocus").focus(),$("#modal_update").addClass("is-active"),$("html").addClass("is-clipped"),l>0?$(".set_expense").trigger("click"):$(".set_duration").trigger("click"),initpicker("#modal_update")}function get_current_datetime(){var e=new Date;return e.getFullYear()+("0"+(e.getMonth()+1)).slice(-2)+("0"+e.getDate()).slice(-2)+("00"+e.getHours()).slice(-2)+("00"+e.getMinutes()).slice(-2)}function initpicker(e){$(e+" .modal_datetime").daterangepicker({singleDatePicker:!0,timePicker:!0,timePickerIncrement:1,locale:{format:"YYYY-MM-DD hh:mm A",applyLabel:"Apply",cancelLabel:"Cancel",daysOfWeek:["Su","Mo","Tu","We","Th","Fr","Sa"],monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"]},autoUpdateInput:!0})}function timeConvert(e){var t=e/60,a=Math.floor(t),i=60*(t-a);return a+"h "+Math.round(i)+"m"}function recalculate_costs(){var e=0,t=0,a=0;$(".highlighted").each((function(i){var s=$(this).children().find(".task_value").attr("data-costraw"),d=$(this).children().find(".task_duration").text();e+=parseFloat(s),t+=parseFloat(d),a=i+1})),e=e.toFixed(2),$("#total_selected").text(a),$("#total_mins").text(timeConvert(t)),$("#total_cost").text(e),0==e?$("#tally").fadeOut("fast"):$("#tally").fadeIn("fast")}$(document).ready((function(){$(document).keyup((function(e){27===e.keyCode&&($(".modal").removeClass("is-active"),$("html").removeClass("is-clipped"))})),$(".delete, .modal-background").click((function(){$(".modal").removeClass("is-active"),$("html").removeClass("is-clipped")})),initpicker("#form_add")})),$(".modal_edit").click((function(e){e.preventDefault();var t=$(this).parents(".task_container"),a=t.attr("data-path"),i=t.attr("data-datetime"),s=t.attr("data-duration"),d=t.attr("data-expense");task_handler("edit",t.attr("data-client"),t.attr("data-project"),t.attr("data-description"),i,s,d,a)})),$(".modal_duplicate").click((function(e){e.preventDefault();var t=$(this).parents(".task_container"),a=t.attr("data-duration"),i=t.attr("data-expense");task_handler("duplicate",t.attr("data-client"),t.attr("data-project"),t.attr("data-description"),null,a,i,null)})),$(".modal_delete").click((function(e){e.preventDefault();var t=$(this).parents(".task_container").attr("data-path");$("#modal_delete .modal-card-title").html('<span class="icon is-size-6"><i class="fas fa-trash"></i></span> Delete Task'),$("#modal_delete .modal_path").val(t),$("#modal_delete .modal_delete_path").html(t),$("#modal_delete").addClass("is-active")})),$(".dropdown button").click((function(e){e.preventDefault(),dropdown=$(this).parents(".dropdown"),width=$(this).parents(".dropdown-container").width(),$(this).parents(".dropdown-container").find(".dropdown-content").width(width),$(".dropdown").not(dropdown).removeClass("is-active"),dropdown.toggleClass("is-active")})),$(".dropdown-selection").click((function(e){e.preventDefault();var t=$(this).attr("data-value"),a=$(this).parents(".dropdown-container").find(".input");$(a).val(t),$(".dropdown").removeClass("is-active")})),$(".set_duration").click((function(e){e.preventDefault(),$(".set_duration").removeClass("is-link"),$(".set_duration").addClass("is-white"),$(".set_expense").removeClass("is-white"),$(".set_expense").addClass("is-link"),$(".modal_tasktype").val("duration"),$(".field_duration").is(":hidden")&&($(".field_expense").hide(),$(".field_duration").show())})),$(".set_expense").click((function(e){e.preventDefault(),$(".set_expense").removeClass("is-link"),$(".set_expense").addClass("is-white"),$(".set_duration").removeClass("is-white"),$(".set_duration").addClass("is-link"),$(".modal_tasktype").val("expense"),$(".field_expense").is(":hidden")&&($(".field_duration").hide(),$(".field_expense").show())})),$(".day_header").click((function(e){e.preventDefault(),toggledate=$(this).attr("data-date"),$("."+toggledate+" > .task_container").toggleClass("highlighted"),recalculate_costs()})),$(".table_col:not(.task_col_4)").click((function(e){e.preventDefault(),$(this).parents(".task_container").toggleClass("highlighted"),recalculate_costs()})),$("#floating_bar").click((function(e){e.preventDefault(),$(".task_container").removeClass("highlighted"),recalculate_costs()}));