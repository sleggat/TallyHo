<?php

$path = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$path = $_POST['Path'];

	// Check we're deleting a task file and not a critical system file.
	$dump = get_task_array($path);
	if (!empty($dump['Project']) && !empty($dump['Client'])) {
		// we can assume it's legit and proceed with unlinking the file
		@unlink($path);
		// log_change("Delete",spyc_dump($current),$content.NL.'Client: '.urldecode($client).NL.'Project: '.urldecode($project));
	}

}