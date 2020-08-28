<?php

$pagespeed_before = microtime(true);

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/spyc.php";
require_once __DIR__."/guts/functions.php";
include __DIR__."/template/header.php";
include __DIR__."/guts/update_task.php";

$array = sort_tasks_by_time(find_all_files('data'));
?>
<div class="container">
	This is where the other guys lies.
</div>
<div class="container">
	<div class="table">
		<div class="table_row task_container_header">
			<div class="table_col">Duration</div>
			<div class="table_col">Start Time</div>
			<div class="table_col">Project</div>
			<div class="table_col">Description</div>
			<div class="table_col">Actions</div>
		</div>
<?php
$previous_day = "00000000";
$day_class = "";
foreach ($array as $task) {
	$task_array = get_task_array($task);
	//print_r($task_array);


	// this is where we can do filtering by client/project and dates.
	if ($previous_day != $task_array['Date']) {
		$day_class = "new_day";
	}
	else {
		$day_class = "";
	}
	?>
		<div class="table_row task_container <?= $day_class ?>" 
		data-affects="<?= $task_array['Path'] ?>" 
		data-date="<?= $task_array['Date'] ?>" 
		data-time="<?= $task_array['Time'] ?>" 
		data-duration="<?= $task_array['Duration'] ?>" 
		data-client="<?= $task_array['Client'] ?>" 
		data-project="<?= $task_array['Project'] ?>" 
		data-description="<?= $task_array['Description'] ?>" >
			<div class="table_col">
				<span class="task_duration"><?= $task_array['Duration'] ?></span><span class="italic">&nbsp;mins</span><br>
				<span class="task_value">$ <?= calculate_cost($task_array['Duration'])['formatted']; ?></span>
			</div>
			<div class="table_col">
				<span class="task_time"><?= format_time($task_array['Time']) ?></span><br>
				<span class="italic">on</span>
				<span class="task_date"><?= format_date($task_array['Date']) ?></span>
			</div>
			<div class="table_col">
				<span class="task_project"><?= $task_array['Project'] ?></span><br>
				<span class="italic">for</span>
				<span class="task_client"><?= $task_array['Client'] ?></span>
			</div>
			<div class="table_col task_description"><?= $task_array['Description'] ?></div>
			<div class="table_col action_icons">
				<span class="icon is-medium"><a href="#" class="modal_update"><ion-icon name="create-outline"></ion-icon></a></span>
				<span class="icon is-medium"><a href="#" class="modal_duplicate"><ion-icon name="duplicate-outline"></ion-icon></a></span>
				<span class="icon is-medium"><a href="#" class="modal_delete"><ion-icon name="trash-outline"></ion-icon></a></span>

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

