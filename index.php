<?php

$pagespeed_before = microtime(true);

$page_limit = 50;
$page_start = 0;
$page_end = 50;
$current_page = 0;
$filter_client = "";
$filter_project = "";
$skipped = 0;
$task_number = 0;
$previous_day = "";

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/Spyc.php";
require_once __DIR__."/guts/functions.php";
require_once __DIR__."/guts/vendor/task.inc.php";
require_once __DIR__."/template/header.php";

$newname = ""; // used for .highlight class

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

if (isset($_GET['p'])) {
	$current_page = $_GET['p'];
	$page_start = (($current_page) * $page_limit); 
	$page_end = (($current_page + 1) * $page_limit); 
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['Submit'])) {
	$filter_client = isset($_GET['FilterClient']) ? $_GET['FilterClient'] : '';
	$filter_project = isset($_GET['FilterProject']) ? $_GET['FilterProject'] : '';
}

$all_records = find_all_files('data');
$array = sort_tasks_by_time($all_records);
$clients_and_projects = clients_and_projects($all_records);
$tasks_total = count($array);
$client_options = '"'.implode('","',array_keys($clients_and_projects)).'"';
$additional_js = '
var client_options = {
	data: ['.$client_options.'],
	list: {
		match: {
			enabled: true
		}
	}
}
var project_options = {
	data: ["Email Newsletters", "Planning/Quoting", "Print Design", "IT/Server Maintenance", "Social Media/Promo", "Website Design", "Website Updates"],
	list: {
		match: {
			enabled: true
		}
	}
};
';

// zip_backup('data/', './backups/'.date("Ymd-His").'.zip')

?>
<nav class="nav">
	<div class="container">
		<div class="columns">
			<div class="column is-6">
				TallyHo!
			</div>
			<div class="column is-2">
				<span class="icon_svg">
					<a href="#">
						<img src="template/ionicons-5.1.2.designerpack/add-outline.svg" />
					</a>
				</span>
			</div>
			<div class="column is-4">
				empty
			</div>
		</div>
	</div>
</nav>
<div id="floating_bar">
	<div class="container">
		<div><ion-icon name="close-circle-outline"></ion-icon> Total $<span id="total_cost"></span></div>
	</div>
</div>
<div class="box_filters hide">
	<form class="" method="get">
		<div class="columns is-mobile is-multiline is-variable is-1">
			<div class="column is-4-tablet is-offset-0-mobile">
				<div class="control">
					<input id="filter_client" name="FilterClient" class="input" type="text" value="<?= $filter_client ?>" placeholder="Client" >
				</div>
			</div>
			<div class="column is-4-tablet">
				<div class="control">
					<input id="filter_project" name="FilterProject" class="input" type="text" value="<?= $filter_project ?>" placeholder="Project">
				</div>
			</div>
			<div class="column is-4-tablet">
				<div class="control">
					<button class="button is-link" type="submit" name="Submit" value="filter">
						<span class="icon_svg">
							<img src="template/ionicons-5.1.2.designerpack/filter-outline.svg" />
						</span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="container hide">
	<div class="box_alt quick_add_form">
		<form method="post" action="" id="form_add">
			<div class="columns is-multiline is-variable is-1">
				<div class="column is-3">
					<label class="label">Started</label>
					<div class="control">
						<input id="add_datetime" name="DateTime" class="modal_datetime input" type="text" value="<?= date('Y-m-d h:i A') ?>" required>
					</div>
				</div>
				<div class="column is-1">
					<label class="label">Duration</label>
					<div class="control">
						<input id="add_duration" name="Duration" class="input" type="text" min="5" value="15" required>
					</div>
				</div>
				<div class="column is-4">
					<label class="label">Client</label>
					<div class="control">
						<input id="add_client" name="Client" class="input" type="text" value="<?= get_task_array($array[0])["Client"] ?>" required>
					</div>
				</div>
				<div class="column is-4">
					<label class="label">Project</label>
					<div class="control">
						<input id="add_project" name="Project" class="input" type="text" value="<?= get_task_array($array[0])["Project"] ?>" required>
					</div>
				</div>
				<div class="column is-10">
					<label class="label">Description</label>
					<div class="control">
						<textarea id="add_description" name="Description" class="textarea" type="text" value="" oninput="auto_grow(this)"></textarea>
					</div>
				</div>

				<input id="add_path" name="Path" value="" type="hidden"/>

				<div class="column is-2">
					<label class="label">&nbsp;</label>
					<div class="control">
						<button class="button is-link" type="submit" name="Submit" value="add">Add Task</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="container">
	<div class="columns is-multiline">
		<div class="column is-12-tablet is-12-desktop">
			<div class="box_sheet">

				<?php
				foreach ($array as $task) {

				$task_array = get_task_array($task);

				if ($filter_project != "") {
					if (stristr($task_array['Project'],$filter_project) === false) { $skipped++; continue; }
				}
				if ($filter_client != "") {
					if (stristr($task_array['Client'],$filter_client) === false) { $skipped++; continue; }
				}

				$task_number ++; // work on pagination
				if ($task_number < $page_start || $task_number > $page_end) { continue; }


				if ($previous_day != format_date($task_array['Date'],'Ymd')) {
					$pretty_date = '<span class="pretty_date">'.format_date($task_array['Date'],'d M Y').'</span> <span class="pretty_day">'.format_date($task_array['Date'],'D').'</span>';
					echo '<div class="day_header" data-date="'.format_date($task_array['Date'],'Ymd').'">'.$pretty_date.'</div>';
				}

				if ($task_array['Path'] == $newname) {
					$highlight = " highlight ";
				}
				else {
					$highlight = " ";
				}
				

				$cost = calculate_cost($task_array['Duration'],$task_array['Path']);
				?>
				<div class="table<?= $highlight . format_date($task_array['Date'],'Ymd') ?>">
					<div class="table_row task_container" 
					data-path="<?= $task_array['Path'] ?>" 
					data-datetime="<?= $task_array['Date'] ?>" 
					data-date="<?= $task_array['Date'] ?>"
					data-duration="<?= $task_array['Duration'] ?>" 
					data-client="<?= $task_array['Client'] ?>" 
					data-project="<?= $task_array['Project'] ?>" 
					data-description="<?= $task_array['Description'] ?>" >
					<div class="table_col task_col_0"><div class="heat_<?= timeheat($task_array['Duration']) ?>"></div></div>
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
							<a href="/?FilterClient=<?= $task_array['Client'] ?>&FilterProject=<?= $task_array['Project'] ?>&Submit=filter">
								<img src="template/ionicons-5.1.2.designerpack/skull-outline.svg" />
							</a>
						</span>
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
	<?php 
	$total_pages = count($array) / $page_limit;
	$query = "";
	if ($filter_client || $filter_project) {
		$query = "&FilterClient=".$filter_client."&FilterProject=".$filter_project."&Submit=filter";
	}
	echo '<div class="pagination">';
	for ($i=0; $i < $total_pages; $i++) { 
		if ($i == ($current_page)) {
			echo '<a href="?p='.$i.$query.'" class="page_current">'.($i+1).'</a>';
		}
		else {
			echo '<a href="?p='.$i.$query.'">'.($i+1).'</a>';
		}
	}
	echo '</div>';
	echo '<div class="pagespeed">Churned out in '.($pagespeed_after-$pagespeed_before). " secs. Total tasks: ".$tasks_total."</div>\n";
	?>
		</div>
	</div><!-- end sheet -->
</div><!-- end container -->

<?php
include __DIR__."/template/footer.php";
?>

