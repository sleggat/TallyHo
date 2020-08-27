<?php

$pagespeed_before = microtime(true);

require_once __DIR__."/guts/config.php";
require_once __DIR__."/guts/vendor/mustangostang/spyc/spyc.php";
require_once __DIR__."/guts/functions.php";
include __DIR__."/template/header.php";

$array = sort_tasks_by_time(find_all_files('data'));
?>
<div class="container">
	<div class="task_container_header">
		<div class="task_duration">Duration</div>
		<div class="task_date_container">
			<div class="task_time">Started</div>
			<div class="task_date">Date</div>
		</div>
		<div class="task_project_container">
			<div class="task_client">Client</div>
			<div class="task_project">/ Project</div>
		</div>
		<div class="task_description">Description</div>
	</div>
<?php
foreach ($array as $task) {
	$task_array = get_task_array($task);
	//print_r($task_array);
	?>
	<div class="task_container modal_trigger" data-affects="<?= $task_array['Path'] ?>">
		<a href="#" >Edit</a>
		<div class="task_duration"><?= $task_array['Duration'] ?></div>
		<div class="task_date_container">

			<div class="task_time"><?= $task_array['Time'] ?></div>
			<div class="task_date"><?= $task_array['Date'] ?></div>
		</div>
		<div class="task_project_container">
			<div class="task_client"><?= $task_array['Client'] ?></div>
			<div class="task_project"><?= $task_array['Project'] ?></div>
		</div>
		<div class="task_description"><?= $task_array['Description'] ?></div>
	</div>
	<?php
}

$pagespeed_after = microtime(true);
echo ($pagespeed_after-$pagespeed_before) . "sec\n";
?>
</div><!-- end container -->
<?php
include __DIR__."/template/footer.php";
?>

