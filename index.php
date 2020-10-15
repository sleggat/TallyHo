<?php

$pagespeed_before = microtime(true);

$page_limit = 100;
$page_start = 0;
$page_end = 100;
$current_page = 0;
$filter_client = "";
$filter_project = "";
$skipped = 0;
$task_number = 0;
$previous_day = "";
$tally['cost'] = 0;
$tally['time'] = 0;


require_once __DIR__ . "/guts/config.php";
require_once __DIR__ . "/guts/vendor/mustangostang/spyc/Spyc.php";
require_once __DIR__ . "/guts/functions.php";
require_once __DIR__ . "/guts/header.php";

$just_updated = ""; // used for .highlight class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// var_dump($_POST);
	if ($_POST['Submit'] == 'update') {
		include __DIR__ . "/guts/update_task.php"; // update and duplicate
	}
	if ($_POST['Submit'] == 'add') {
		include __DIR__ . "/guts/add_task.php"; // add (plan to merge with update_task)
	}
	if ($_POST['Submit'] == 'delete') {
		include __DIR__ . "/guts/delete_task.php"; // delete
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
	$page_limit = 9999999; // don't bother with pagination when there are filters
}

$file_structure = find_all_files(DATA_PATH);
$all_records_array = $file_structure[0];
$info_array = get_info_array($file_structure[1]);

$all_records_sorted_array = sort_tasks_by_time($all_records_array);

$clients_and_projects = clients_and_projects($all_records_array);
$tasks_total = count($all_records_sorted_array);
$client_options = '"' . implode('","', array_keys($clients_and_projects)) . '"';
$project_options = '"' . implode('","', array_unique(call_user_func_array('array_merge', ($clients_and_projects)))) . '"';

$client_array = array_keys($clients_and_projects);
$project_array = array_unique(call_user_func_array('array_merge', ($clients_and_projects)));

$last_tasks = get_last_tasks($all_records_sorted_array, 5);
$additional_js = ''; // extra JS to go in footer

// Not implemented yet, but you can occasionally 'un-comment' this to backup your data folder. 
// Note: runs every time the page loads, so you'll want to 'comment' it again after use!
// zip_backup(DATA_PATH . '/', './backups/' . date("Ymd-His") . '.zip')

?>

<nav class="nav" role="navigation" aria-label="dropdown navigation">
	<div class="container">
		<div class="level is-mobile">
			<div class="level-left">
				<div class="level-item">
					<a href="./" class="logo"><img src="guts/images/logo-white.png" alt="TallyHo!"></a>
				</div>

				<div class="level-item">
					<div class="dropdown">
						<div class="dropdown-trigger">
							<button class="button is-small" aria-haspopup="true" aria-controls="add-dropdown-menu">
								<span class="icon is-small has-text-link">
									<i class="fas fa-plus" aria-hidden="true"></i>
								</span>
							</button>
						</div>
						<div class="dropdown-menu" id="add-dropdown-menu" role="menu">
							<div class="dropdown-content">
								<a href="#" class="dropdown-item" onClick="task_handler('add',null,null,null,null,null,null)">Add New Task</a>
								<hr class="dropdown-divider">
								<?php
								foreach ($last_tasks as $task) {
									echo '<a href="#" class="dropdown-item" onClick="task_handler(\'continue\',\'' . $task[0] . '\',\'' . $task[1] . '\',null,null,null,null);"><span>' . $task[0] . ' / ' . $task[1] . '</span></a>';
								}
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="level-item has-text-white tally">
					<div id="tally" class="hide">
						<div class="hide">Rows: <span id="total_selected"></span></div>
						<div>Time: <span id="total_mins"></span></div>
						<div>Total: <?= CURRENCY_SYMBOL ?><span id="total_cost"></span></div>
					</div>
				</div>

			</div>
			<div class="level-right">
				<div class="level-item">
					<form method="get">
						<div class="box_filters">
							<?= output_dropdown($client_array, 'FilterClient', 'is-small', $filter_client, 'Client'); ?>
							<?= output_dropdown($project_array, 'FilterProject', 'is-small', $filter_project, 'Project'); ?>
							<div class="control">
								<button class="button is-small is-link" type="submit" name="Submit" value="filter"><span class="icon is-small"><i class="fas fa-search" aria-hidden="true"></i></span></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</nav>

<div class="container">
	<div class="columns">
		<div class="column">
			<div class="box_sheet">
				<div class="page_number">Page <?= $current_page + 1 ?></div>

				<?php

				// start of foreach loop

				foreach ($all_records_sorted_array as $task) {
					$add_classes = "";

					$current_task = get_task_array($task);

					if (!empty($info_array[$current_task['Client']]['Invoiced'])) {
						if ($info_array[$current_task['Client']]['Invoiced'] >= format_date($current_task['Date'], 'Ymd')) {
							$add_classes .= "show_invoiced ";
						}
					} elseif (!empty($info_array[$current_task['Client']][$current_task['Project']]['Invoiced'])) {
						if ($info_array[$current_task['Client']][$current_task['Project']]['Invoiced'] >= format_date($current_task['Date'], 'Ymd')) {
							$add_classes .= "show_invoiced ";
						}
					}

					if ($filter_project != "") {
						if (stristr($current_task['Project'], $filter_project) === false) {
							$skipped++;
							continue;
						}
					}
					if ($filter_client != "") {
						if (stristr($current_task['Client'], $filter_client) === false) {
							$skipped++;
							continue;
						}
					}

					$task_number++; // work on pagination
					if ($task_number < $page_start || $task_number > $page_end) {
						continue;
					}


					if ($previous_day != format_date($current_task['Date'], 'Ymd')) {
						$pretty_date = '<span class="pretty_date">' . format_date($current_task['Date'], 'j M Y') . '</span> <span class="pretty_day">' . format_date($current_task['Date'], 'l') . '</span>';
						echo '<div class="day_header" data-date="' . format_date($current_task['Date'], 'Ymd') . '">' . $pretty_date . '</div>';
					}

					if ($current_task['Path'] == $just_updated) {
						$add_classes .= "show_justupdated ";
					}

					if (!empty($current_task['Expense'])) {
						$cost = calculate_cost($current_task['Expense'], '', true);
						$current_task['Duration'] = false;
					} else {
						$cost = calculate_cost($current_task['Duration'], $current_task['Path'], false);
						$current_task['Expense'] = false;
					}

					// add up totals
					$tally['cost'] = $tally['cost'] + $cost['raw'];
					$tally['time'] = $tally['time'] + $current_task['Duration'];

				?>
					<div class="<?= $add_classes . format_date($current_task['Date'], 'Ymd') ?>">
						<div class="table_row task_container" data-path="<?= $current_task['Path'] ?>" data-datetime="<?= $current_task['Date'] ?>" data-date="<?= $current_task['Date'] ?>" data-duration="<?= $current_task['Duration'] ?>" data-expense="<?= $current_task['Expense'] ?>" data-client="<?= $current_task['Client'] ?>" data-project="<?= $current_task['Project'] ?>" data-description="<?= $current_task['Description'] ?>">
							<div class="table_col task_col_1 heat_<?= timeheat($current_task['Duration']) ?>">
								<span class="task_duration">
									<?php if (!empty($current_task['Expense'])) {
										echo 'N/A</span>';
									} else {
										echo $current_task['Duration'] . ' </span><span class="task_duration_label">mins</span>';
									}
									?><br>
									<span class="task_time"><?php
															if (empty($current_task['Expense'])) {
																echo get_starttime($current_task['Date'], $current_task['Duration']) . '-';
															}
															echo format_time($current_task['Date']); ?></span>
							</div>
							<div class="table_col task_col_2">
								<span class="task_value" data-costraw="<?= $cost['raw']; ?>"><span class="task_value_currency"><?= CURRENCY_SYMBOL ?></span><?= $cost['formatted']; ?></span>
								<br><span class="task_value_source"><?= $cost['source']; ?></span>
							</div>
							<div class="table_col task_col_3">
								<span class="task_client"><?= $current_task['Client'] ?></span>
								<span class="task_project"><?= $current_task['Project'] ?></span><br>
								<span class="task_description"><?= nl2br($current_task['Description']) ?></span>
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
												<span class="icon has-text-link"><i class="far fa-copy"></i></span>Duplicate
											</a>
											<a href="#" class="dropdown-item modal_edit">
												<span class="icon has-text-link"><i class="far fa-edit"></i></span>Edit
											</a>
											<hr class="dropdown-divider">
											<a href="/?FilterClient=<?= $current_task['Client'] ?>&FilterProject=<?= $current_task['Project'] ?>&Submit=filter" class="dropdown-item" alt="Filter this Client/Project">
												<span class="icon has-text-link"><i class="fas fa-search"></i></span>Filter
											</a>

											<hr class="dropdown-divider">
											<a href="#" class="dropdown-item modal_delete">
												<span class="icon has-text-danger"><i class="far fa-trash-alt"></i></span>Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
					$previous_day = format_date($current_task['Date'], 'Ymd');
				}

				// end of foreach loop

				$pagespeed_after = microtime(true);

				?>
			</div><!-- end table -->
			<?php
			$query = "";
			if ($filter_client || $filter_project) {
				$query = "&FilterClient=" . $filter_client . "&FilterProject=" . $filter_project . "&Submit=filter";
				$total_pages = 1;
			} else {
				$total_pages = $tasks_total / $page_limit;
			}
			echo '<nav class="pagination" role="navigation" aria-label="pagination">
			<ul class="pagination-list">';
			for ($i = 0; $i < $total_pages; $i++) {
				if ($i == ($current_page)) {
					echo '<li><a href="?p=' . $i . $query . '" class="pagination-link is-current" aria-label="Goto page ' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
				} else {
					echo '<li><a href="?p=' . $i . $query . '" class="pagination-link" aria-label="Goto page ' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
				}
			}
			echo '</ul>
			</nav>';
			echo '<div class="page_tally">Page Tally - Cost: ' . CURRENCY_SYMBOL . ' ' . $tally['cost'] . ' / Time: ' . $tally['time'] . ' mins</div>';
			echo '<div class="pagespeed">Churned out in ' . ($pagespeed_after - $pagespeed_before) . " secs. Total tasks: " . $tasks_total . "</div>\n";
			?>
		</div>
	</div><!-- end sheet -->
</div><!-- end container -->

<?php
include __DIR__ . "/guts/footer.php";
?>