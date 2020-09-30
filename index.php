<?php

$pagespeed_before = microtime(true);

$page_limit = 100;
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
// require_once __DIR__."/guts/vendor/task.inc.php";
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
$project_options = '"'.implode('","',array_unique(call_user_func_array('array_merge', ($clients_and_projects)))).'"';
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
	data: ['.$project_options.'],
	list: {
		match: {
			enabled: true
		}
	}
};
';

// zip_backup('data/', './backups/'.date("Ymd-His").'.zip')

?>
<nav class="navbar is-fixed-top" role="navigation" aria-label="dropdown navigation">
	<div class="container">
		<div class="navbar-brand">
			<a href="/" class="navbar-item"><img src="template/images/logo-white.png" alt="TallyHo!"></a>
			<div class="navbar-item dropdown-container">
				<div class="dropdown is-hoverable">
					<div class="dropdown-trigger">
						<button class="button modal_add is-small" aria-haspopup="true" aria-controls="dropdown-menu">
							<span class="icon is-small has-text-link">
								<i class="fas fa-plus" aria-hidden="true"></i>
							</span>
						</button>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div class="dropdown-content">
							<a href="#" class="dropdown-item">Hello</a>
						</div>
					</div>
				</div>
			</div>
			<div class="tally navbar-item has-text-primary">
				<div id="tally" class="hide">
					Rows: <span id="total_selected"></span> / 
					Time: <span id="total_mins"></span> / 
					Total: <?= CURRENCY_SYMBOL ?><span id="total_cost"></span>
				</div>
			</div>
		</div>
	</div>
</nav>

<div class="container">
	<div class="columns">
		<div class="column">
			<form method="get">
				<div class="box_filters">
					<div class="field has-addons has-addons-right">
						<div class="control">
							<input id="filter_client" name="FilterClient" class="input" type="text" value="<?= $filter_client ?>" placeholder="Client" >
						</div>
						<div class="control">
							<input id="filter_project" name="FilterProject" class="input" type="text" value="<?= $filter_project ?>" placeholder="Project">
						</div>
						<div class="control">
							<button class="button" type="submit" name="Submit" value="filter">
								Filter
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="container">
	<div class="columns">
		<div class="column">
			<div class="box_sheet">
				<div class="page_number">Page <?= $current_page+1 ?></div>

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
						$pretty_date = '<span class="pretty_date">'.format_date($task_array['Date'],'j M Y').'</span> <span class="pretty_day">'.format_date($task_array['Date'],'l').'</span>';
						echo '<div class="day_header" data-date="'.format_date($task_array['Date'],'Ymd').'">'.$pretty_date.'</div>';
					}

					if ($task_array['Path'] == $newname) {
						$highlight = " highlight ";
					}
					else {
						$highlight = " ";
					}

					if (!empty($task_array['Expense'])) {
						$cost = calculate_cost($task_array['Expense'],'', true);
						$task_array['Duration'] = false;
					}
					else {
						$cost = calculate_cost($task_array['Duration'],$task_array['Path'],false);
						$task_array['Expense'] = false;
					}

					

					?>
					<div class="<?= $highlight . format_date($task_array['Date'],'Ymd') ?>">
						<div class="table_row task_container" 
						data-path="<?= $task_array['Path'] ?>" 
						data-datetime="<?= $task_array['Date'] ?>" 
						data-date="<?= $task_array['Date'] ?>"
						data-duration="<?= $task_array['Duration'] ?>" 
						data-expense="<?= $task_array['Expense'] ?>" 
						data-client="<?= $task_array['Client'] ?>" 
						data-project="<?= $task_array['Project'] ?>" 
						data-description="<?= $task_array['Description'] ?>" >
						<div class="table_col task_col_1 heat_<?= timeheat($task_array['Duration']) ?>">
							<span class="task_duration">
								<?php if (!empty($task_array['Expense'])) {
									echo 'N/A</span>';
								} 
								else {
									echo $task_array['Duration'].' </span><span class="task_duration_label">mins</span>';
								}
								?><br>
								<span class="task_time"><?= format_time($task_array['Date']) ?></span>
							</div>
							<div class="table_col task_col_2">
								<span class="task_value" data-costraw="<?= $cost['raw']; ?>"><span class="task_value_currency"><?= CURRENCY_SYMBOL ?></span><?= $cost['formatted']; ?></span>
								<br><span class="task_value_source"><?= $cost['source']; ?></span>
							</div>
							<div class="table_col task_col_3">
								<span class="task_client"><?= $task_array['Client'] ?></span>
								<span class="task_project"><?= $task_array['Project'] ?></span><br>
								<span class="task_description"><?= $task_array['Description'] ?></span>
							</div>
							<div class="table_col task_col_4">
								<div class="dropdown is-right is-hoverable">
									<div class="dropdown-trigger">
										<button class="button is-small" aria-haspopup="true" aria-controls="dropdown-menu">
											<span class="icon is-small has-text-link">
												<i class="fas fa-angle-down" aria-hidden="true"></i>
											</span>
										</button>
									</div>
									<div class="dropdown-menu" id="dropdown-menu" role="menu">
										<div class="dropdown-content">
											<a href="#" class="dropdown-item modal_duplicate">
												<span class="icon has-text-link"><i class="fas fa-copy"></i></span>Duplicate
											</a>
											<a href="#" class="dropdown-item modal_update">
												<span class="icon has-text-link"><i class="fas fa-edit"></i></span>Edit
											</a>
											<hr class="dropdown-divider">
											<a href="/?FilterClient=<?= $task_array['Client'] ?>&FilterProject=<?= $task_array['Project'] ?>&Submit=filter" class="dropdown-item" alt="Filter this Client/Project">
												<span class="icon has-text-link"><i class="fas fa-filter"></i></span>Filter
											</a>
											
											<hr class="dropdown-divider">
											<a href="#" class="dropdown-item modal_delete">
												<span class="icon has-text-danger"><i class="fas fa-trash"></i></span>Delete
											</a>
										</div>
									</div>
								</div>
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
			echo '<nav class="pagination" role="navigation" aria-label="pagination">
			<ul class="pagination-list">';
			for ($i=0; $i < $total_pages; $i++) { 
				if ($i == ($current_page)) {
					echo '<li><a href="?p='.$i.$query.'" class="pagination-link is-current" aria-label="Goto page '.($i+1).'">'.($i+1).'</a></li>';
				}
				else {
					echo '<li><a href="?p='.$i.$query.'" class="pagination-link" aria-label="Goto page '.($i+1).'">'.($i+1).'</a></li>';
				}
			}
			echo '</ul>
			</nav>';
			echo '<div class="pagespeed">Churned out in '.($pagespeed_after-$pagespeed_before). " secs. Total tasks: ".$tasks_total."</div>\n";
			?>
		</div>
	</div><!-- end sheet -->
</div><!-- end container -->

<?php
include __DIR__."/template/footer.php";
?>

