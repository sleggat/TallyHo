<?php

$pagespeed_before = microtime(true);

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/spyc.php";
require_once __DIR__."/guts/functions.php";
include __DIR__."/template/header.php";

$all_records = find_all_files('data');
$array = sort_tasks_by_time($all_records);
$clients_and_projects = clients_and_projects($all_records);
// print_r($clients_and_projects);
$tasks_total = count($array);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//var_dump($_POST);
	if ($_POST['Submit'] == 'update') {
		include __DIR__."/guts/update_task.php"; // update and duplicate
	}
	if ($_POST['Submit'] == 'add') {
		include __DIR__."/guts/add_task.php"; // update and duplicate
	}
}

?><div id="floating_bar">
	<div class="container">
		<div><ion-icon name="close-circle-outline"></ion-icon> Total $<span id="total_cost"></span></div>
	</div>
</div>
<div class="head">
	<div class="container">
		<h1>TallyHo!</h1>
		This is where the other stuff goes.<br>
		Add; new task, new client, new project<br>
		Tools: remove empty client/project folders<br>
		Filters<br>
		<?= 'Total tasks'.$tasks_total; ?>
		<br>
		<br>
		<h3><ion-icon name="add-circle-outline"></ion-icon> New Task</h3>
		<form method="post" class="form" action="" id="form_add">
			<div class="field span-2">
				<label class="label">Date & Time</label>
				<div class="control">
					<input id="add_datetime" name="DateTime" class="input" type="text" value=""required>
				</div>
			</div>
			<div class="field span-1">
				<label class="label">Duration</label>
				<div class="control">
					<input id="add_duration" name="Duration" class="input" type="number" step="5" min="5" value="" required>
				</div>
			</div>
			<div class="field span-1">
				<label class="label">Client</label>
				<div class="control">
					<input id="add_client" name="Client" class="input" type="text" value="" required>
				</div>
			</div>
			<div class="field span-2">
				<label class="label">Project</label>
				<div class="control">
					<input id="add_project" name="Project" class="input" type="text" value="" required>
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Description</label>
				<div class="control">
					<input id="add_description" name="Description" class="input" type="text" value="" >
				</div>
			</div>

			<input id="add_affects" name="Affects" value="" type="hidden"/>

			<div class="field span-3">
				<div class="control">
					<button class="button is-link" type="submit" name="Submit" value="add">Submit</button>
				</div>
			</div>

		</form>
	</div>
</div>
<div class="container">
	<div class="sheet">

	<?php
	$previous_day = "";
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
			echo '<div class="day_header" data-date="'.$task_array['Date'].'">'.format_date($task_array['Date']).'</div>';
		}

		$cost = calculate_cost($task_array['Duration'],$task_array['Path']);
		?>
		<div class="table <?= $task_array['Date'] ?>">
			<div class="table_row task_container" 
				data-affects="<?= $task_array['Path'] ?>" 
				data-datetime="<?= format_datetime($task_array['Date'], $task_array['Time']) ?>" 
				data-date="<?= $task_array['Date'] ?>" 
				data-time="<?= $task_array['Time'] ?>" 
				data-duration="<?= $task_array['Duration'] ?>" 
				data-client="<?= $task_array['Client'] ?>" 
				data-project="<?= $task_array['Project'] ?>" 
				data-description="<?= $task_array['Description'] ?>" >
				<div class="table_col task_col_graph"><div class="heat_<?= timeheat($task_array['Duration']) ?>"></div></div>
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
	</div><!-- end sheet -->
</div><!-- end container -->
<?php
echo ($pagespeed_after-$pagespeed_before) . "sec\n";
include __DIR__."/template/footer.php";
?>

