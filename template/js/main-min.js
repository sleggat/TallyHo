function task_handler(t,a,e,l,i,o,s){switch(event.preventDefault(),autofocus="client",o=parseFloat(o)?o:15,t){case"continue":header='<span class="icon is-size-6"><i class="far fa-clone"></i></span> Continue Task',autofocus="description";break;case"duplicate":header='<span class="icon is-size-6"><i class="fas fa-copy"></i></span> Duplicate Task',i=get_current_datetime();break;case"add":header='<span class="icon is-size-6"><i class="fas fa-plus"></i></span> Add Task',i=get_current_datetime();break;case"edit":header='<span class="icon is-size-6"><i class="fas fa-edit"></i></span> Edit Task'}$("#modal_update .modal-card-title").html(header),$("#modal_update .modal_datetime").val(i),$("#modal_update .modal_duration").val(o),$("#modal_update .modal_client").val(a),$("#modal_update .modal_project").val(e),$("#modal_update .modal_description").val(l),$("#modal_update .modal_path").val(s),$("#modal_update .modal_"+autofocus).attr("autofocus","autofocus").focus(),$("#modal_update").addClass("is-active"),initpicker("#modal_update")}function get_current_datetime(){var t=new Date;return t.getFullYear()+("0"+(t.getMonth()+1)).slice(-2)+("0"+t.getDate()).slice(-2)+("00"+t.getHours()).slice(-2)+("00"+t.getMinutes()).slice(-2)}function initpicker(t){$(t+" .modal_datetime").daterangepicker({singleDatePicker:!0,timePicker:!0,timePickerIncrement:1,locale:{format:"YYYY-MM-DD hh:mm A",applyLabel:"Apply",cancelLabel:"Cancel",daysOfWeek:["Su","Mo","Tu","We","Th","Fr","Sa"],monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"]},autoUpdateInput:!0})}function auto_grow(t){t.style.height="5px",t.style.height=t.scrollHeight+"px"}function timeConvert(t){var a=t/60,e=Math.floor(a),l=60*(a-e);return e+"h "+Math.round(l)+"m"}function recalculate_costs(){var t=0,a=0,e=0;$(".highlighted").each((function(l){var i=$(this).children().find(".task_value").attr("data-costraw"),o=$(this).children().find(".task_duration").text();t+=parseFloat(i),a+=parseFloat(o),e=l+1})),t=t.toFixed(2),$("#total_selected").text(e),$("#total_mins").text(timeConvert(a)),$("#total_cost").text(t),0==t?$("#tally").fadeOut("fast"):$("#tally").fadeIn("fast")}$(document).ready((function(){$(document).keyup((function(t){27===t.keyCode&&$(".modal").removeClass("is-active")})),$(".delete, .modal-background").click((function(){$(".modal").removeClass("is-active")})),initpicker("#form_add"),$(".modal_client").easyAutocomplete(client_options),$(".modal_project").easyAutocomplete(project_options),$("#filter_client").easyAutocomplete(client_options),$("#filter_project").easyAutocomplete(project_options)})),$(".modal_edit").click((function(t){t.preventDefault();var a=$(this).parents(".task_container"),e=a.attr("data-path"),l=a.attr("data-datetime"),i=a.attr("data-duration");task_handler("edit",a.attr("data-client"),a.attr("data-project"),a.attr("data-description"),l,i,e)})),$(".modal_duplicate").click((function(t){t.preventDefault();var a=$(this).parents(".task_container"),e=a.attr("data-duration");task_handler("duplicate",a.attr("data-client"),a.attr("data-project"),a.attr("data-description"),null,e,null)})),$(".modal_delete").click((function(t){t.preventDefault();var a=$(this).parents(".task_container").attr("data-path");$("#modal_delete .modal-card-title").html('<span class="icon is-size-6"><i class="fas fa-trash"></i></span> Delete Task'),$("#modal_delete .modal_path").val(a),$("#modal_delete .modal_delete_path").html(a),$("#modal_delete").addClass("is-active")})),$(".day_header").click((function(t){t.preventDefault(),toggledate=$(this).attr("data-date"),$("."+toggledate+" > .task_container").toggleClass("highlighted"),recalculate_costs()})),$(".table_col:not(.task_col_4)").click((function(t){t.preventDefault(),$(this).parents(".task_container").toggleClass("highlighted"),recalculate_costs()})),$("#floating_bar").click((function(t){t.preventDefault(),$(".task_container").removeClass("highlighted"),recalculate_costs()}));