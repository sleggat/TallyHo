<?php

$datetime = false;
$date = false;
$time = false;
$duration = false;
$expense = false;
$tasktype = false;
$client = false;
$project = false;
$description = false;
$path = false;
$current = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$datetime = trim($_POST['DateTime']);
	$date = format_date($datetime, 'Ymd');
	$time = format_date($datetime, 'Hi');
	$duration = $_POST['Duration'];
	$expense = $_POST['Expense'];
	$tasktype = $_POST['TaskType'];
	$client = urlencode(trim($_POST['Client']));
	$project = urlencode(trim($_POST['Project']));
	$description = addslashes(trim($_POST['Description']));

	$content = "";
	$content .= "Date: '" . date('Y-m-d h:i A') . "'" . NL;
	$content .= "DateUpdated: '" . date('Y-m-d h:i A') . "'" . NL;
	if ($tasktype == 'expense') {
		$content .= "Expense: " . $expense . NL;
	} else {
		$content .= "Duration: " . $duration . NL;
	}
	$content .= "Description: \"" . $description . "\"";


	$newfolder = DATA_PATH . '/' . $client . '/' . $project;
	$newname = $newfolder . '/' . $date . '-' . $time . '.txt';

	if (($client != $current['Client']) || ($project != $current['Project'])) {
		@mkdir($newfolder, 0777, true);
	}

	if ($_POST['Path'] != '') {
		$path = $_POST['Path'];
		$current = get_task_array($path);
		rename($path, $newname);
	} else {
		// must be duplicating a task so let's create a new path
		$current['Client'] = "";
		$current['Project'] = "";
	}

	$fp = fopen($newname, 'w');
	fwrite($fp, $content);
	fclose($fp);

	log_change("Add", '', $content . NL . 'Client: ' . urldecode($client) . NL . 'Project: ' . urldecode($project));
} else {
	//echo "nothing submitted yet";
}
