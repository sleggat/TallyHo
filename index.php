<?php

$pagespeed_before = microtime(true);

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/spyc.php";
require_once __DIR__."/guts/functions.php";
include __DIR__."/template/header.php";
include __DIR__."/guts/update_task.php";

$array = sort_tasks_by_time(find_all_files('data'));
?>
<div id="floating_bar">
	<div class="container">
		<div>Total $<span id="total_cost"></span></div>
	</div>
</div>
<div class="container">
	This is where the other stuff goes.<br>
	Add; new task, new client, new project<br>
	Tools: remove empty client/project folders<br>
	Filters<br>
	<br>
	<br>
</div>
<div class="container">
<!-- 		<div class="table_row task_container_header">
			<div class="table_col">Duration</div>
			<div class="table_col">Start Time</div>
			<div class="table_col">Project</div>
			<div class="table_col">Description</div>
			<div class="table_col">Actions</div>
		</div> -->
<?php
$previous_day = "00000000";
$day_class = "";
foreach ($array as $task) {

	$task_array = get_task_array($task);
	//print_r($task_array);


	// this is where we can do filtering by client/project and dates.
	// client, project, client+project would be the first to do
	// partial matching

	// do a case insensitive match
	//if ($task_array['Project'] != "Website") { continue; }
	//if ($task_array['Client'] != "Magrette") { continue; }

	if ($previous_day != $task_array['Date']) {
		echo '<div class="new_day" data-date="'.$task_array['Date'].'">'.format_date($task_array['Date']).'</div>';
	}

	$cost = calculate_cost($task_array['Duration'],$task_array['Path']);
	?>
	<div class="table <?= $task_array['Date'] ?>">
		<div class="table_row task_container" 
			data-affects="<?= $task_array['Path'] ?>" 
			data-date="<?= $task_array['Date'] ?>" 
			data-time="<?= $task_array['Time'] ?>" 
			data-duration="<?= $task_array['Duration'] ?>" 
			data-client="<?= $task_array['Client'] ?>" 
			data-project="<?= $task_array['Project'] ?>" 
			data-description="<?= $task_array['Description'] ?>" >
			<div class="table_col task_col_graph"><span class="heat_<?= timeheat($task_array['Duration']) ?>"></span></div>
			<div class="table_col task_col_1">
				<span class="task_duration"><?= $task_array['Duration'] ?></span> <span>mins</span><br>
				<span class="task_time"><?= format_time($task_array['Time']) ?></span>
			</div>
			<div class="table_col task_col_2">
				<span class="task_value" data-costraw="<?= $cost['raw']; ?>"><span class="task_value_currency">$</span><?= $cost['formatted']; ?></span>
				<br><span class="task_value_source"><?= $cost['source']; ?></span>
			</div>
			<div class="table_col task_col_3">
				<span class="task_client"><?= $task_array['Client'] ?></span>
				<span class="task_project"><?= $task_array['Project'] ?></span><br>
				<span class="task_description"><?= $task_array['Description'] ?></span>
			</div>
			<div class="table_col  task_col_4 action_icons">
				<span class="icon is-medium"><a href="#" class="modal_update"><ion-icon name="create-outline"></ion-icon></a></span>
				<span class="icon is-medium"><a href="#" class="modal_duplicate"><ion-icon name="duplicate-outline"></ion-icon></a></span>
				<span class="icon is-medium"><a href="#" class="modal_delete"><ion-icon name="trash-outline"></ion-icon></a></span>

			</div>
		</div>
	</div>
	<?php
	$previous_day = $task_array['Date'];
}

$pagespeed_after = microtime(true);

?>
	</div><!-- end table -->
</div><!-- end container -->
<?php
echo ($pagespeed_after-$pagespeed_before) . "sec\n";
include __DIR__."/template/footer.php";
?>

