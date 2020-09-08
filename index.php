<?php

$pagespeed_before = microtime(true);

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/spyc.php";
require_once __DIR__."/guts/functions.php";
require_once __DIR__."/template/header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// var_dump($_POST);
	if ($_POST['Submit'] == 'update') {
		include __DIR__."/guts/update_task.php"; // update and duplicate
	}
	if ($_POST['Submit'] == 'add') {
		include __DIR__."/guts/add_task.php"; // update and duplicate
	}
	if ($_POST['Submit'] == 'delete') {
		include __DIR__."/guts/delete_task.php"; // delete
	}
}

$filter_client = "";
$filter_project = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$filter_client = isset($_GET['FilterClient']) ? $_GET['FilterClient'] : '';
	$filter_project = isset($_GET['FilterProject']) ? $_GET['FilterProject'] : '';
}

$all_records = find_all_files('data');
$array = sort_tasks_by_time($all_records);
$clients_and_projects = clients_and_projects($all_records);
//print_r($clients_and_projects);
//print_r($array);
$tasks_total = count($array);
//foreach (array_keys($clients_and_projects) as $client) {
$client_options = '"'.implode('","',array_keys($clients_and_projects)).'"';
//echo $client_options;
//}
$additional_js = '
	var client_options = {
    	data: ['.$client_options.']
	}
	var project_options = {
    	data: ["Email Newsletters", "Planning/Quoting", "Print Design", "IT/Server Maintenance", "Social Media/Promo", "Website"]
	};
	';


// zip_backup('data/', './backups/'.date("Ymd-His").'.zip')

?><div id="floating_bar">
	<div class="container">
		<div><ion-icon name="close-circle-outline"></ion-icon> Total $<span id="total_cost"></span></div>
	</div>
</div>
<div class="head">
	<div class="container">
		<div class="logo"><img src="template/images/logo2.png"></div>
		<div class="hide">This is where the other stuff goes.<br>
		Add; new task, new client, new project<br>
		Tools: remove empty client/project folders<br>
		Filters<br>
		<?= 'Total tasks'.$tasks_total; ?>
		</div>
		<h3><span class="icon_svg">Quick Add</h3>
		<form method="post" class="grid-6" action="" id="form_add">
			<div class="field span-4">
				<label class="label">Started</label>
				<div class="control">
					<input id="add_datetime" name="DateTime" class="DateTime input" type="text" value="<?= date('Y-m-d h:i A') ?>" required>
				</div>
			</div>
			<div class="field span-2">
				<label class="label">Duration</label>
				<div class="control">
					<input id="add_duration" name="Duration" class="input" type="number" step="5" min="5" value="15" required>
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Client</label>
				<div class="control">
					<input id="add_client" name="Client" class="input" type="text" value="<?= get_task_array($array[0])["Client"] ?>" required>
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Project</label>
				<div class="control">
					<input id="add_project" name="Project" class="input" type="text" value="<?= get_task_array($array[0])["Project"] ?>" required>
				</div>
			</div>
			<div class="field span-6">
				<label class="label">Description</label>
				<div class="control">
					<input id="add_description" name="Description" class="input" type="text" value="" >
				</div>
			</div>

			<input id="add_path" name="Path" value="" type="hidden"/>

			<div class="field span-3">
				<div class="control">
					<button class="button is-link" type="submit" name="Submit" value="add">Add Task</button>
				</div>
			</div>

		</form>
	</div>
</div>
<div class="container">
	<div class="sheet">
		<div class="filters grid-6">
			<form class="span-5" method="get" >
				<div class="filters_form hide">
					<div class="grid-6">
						<div class="field span-2">
							<div class="control">
								<input id="filter_client" name="FilterClient" class="input" type="text" value="<?= $filter_client ?>" placeholder="Client" >
							</div>
						</div>
						<div class="field span-2">
							<div class="control">
								<input id="filter_project" name="FilterProject" class="input" type="text" value="<?= $filter_project ?>" placeholder="Project">
							</div>
						</div>
						<div class="field span-2">
							<div class="control">
								<button class="button is-link" type="submit" name="Submit" value="filter">Add Task</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="toggle_filters span-1">
				<span class="icon_svg hide">
					<a href="#" class="">
						<img src="template/ionicons-5.1.2.designerpack/skull-outline.svg" />
					</a>
				</span>
			</div>
		</div>

	<?php
	$tasks_displayed = 0;
	$previous_day = "";
	foreach ($array as $task) {

		$task_array = get_task_array($task);


		// this is where we can do filtering by client/project and dates.
		// client, project, client+project would be the first to do
		// partial matching

		// do a case insensitive match
		if ($filter_project != "") {
			if (stristr($task_array['Project'],$filter_project) === false) { continue; }
		}
		if ($filter_client != "") {
			if (stristr($task_array['Client'],$filter_client) === false) { continue; }
		}
		if ($previous_day != format_date($task_array['Date'],'Ymd')) {
			echo '<div class="day_header" data-date="'.format_date($task_array['Date'],'Ymd').'">'.format_date($task_array['Date'],'D d M Y').'</div>';
		}

		$tasks_displayed ++; // work on pagination
		if ($tasks_displayed > 50) { break; }

		$cost = calculate_cost($task_array['Duration'],$task_array['Path']);
		?>
		<div class="table <?= format_date($task_array['Date'],'Ymd') ?>">
			<div class="table_row task_container" 
				data-path="<?= $task_array['Path'] ?>" 
				data-datetime="<?= $task_array['Date'] ?>" 
				data-date="<?= $task_array['Date'] ?>"
				data-duration="<?= $task_array['Duration'] ?>" 
				data-client="<?= $task_array['Client'] ?>" 
				data-project="<?= $task_array['Project'] ?>" 
				data-description="<?= $task_array['Description'] ?>" >
				<div class="table_col task_col_graph"><div class="heat_<?= timeheat($task_array['Duration']) ?>"></div></div>
				<div class="table_col task_col_1">
					<span class="task_duration"><?= $task_array['Duration'] ?></span> <span>mins</span><br>
					<span class="task_time"><?= format_time($task_array['Date']) ?></span>
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
					<span class="icon_svg">
						<a href="#" class="modal_update">
							<img src="template/ionicons-5.1.2.designerpack/create-outline.svg" />
						</a>
					</span>
					<span class="icon_svg">
						<a href="#" class="modal_duplicate">
							<img src="template/ionicons-5.1.2.designerpack/duplicate-outline.svg" />
						</a>
					</span>
					<span class="icon_svg">
						<a href="#" class="modal_delete">
							<img src="template/ionicons-5.1.2.designerpack/trash-outline.svg" />
						</a>
					</span>

				</div>
			</div>
		</div>
		<?php
		$previous_day = format_date($task_array['Date'],'Ymd');
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

