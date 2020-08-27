<?php

function scandir_clean($dir) {
	$contents = array_diff(scandir($dir), array('..', '.'));
	return $contents;
}

function find_all_files($dir) {
    $root = scandir($dir);
    foreach($root as $value)
    {
        if($value === '.' || $value === '..' || $value === '.DS_Store') {continue;}
        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;}
        foreach(find_all_files("$dir/$value") as $value)
        {
            $result[]=$value;
        }
    }
    return $result;
}

function sort_tasks_by_time($array) {
	// takes the result of find_all_files()
	usort($array, "cmp");
	return $array;
}

function cmp($a, $b) {
	// sorting companion for sort_tasks_by_time()
    if (substr($a, -17, 14) == substr($b, -17, 14)) {
        return 0;
    }
    return (substr($a, -17, 14) > substr($b, -17, 14)) ? -1 : 1;
}

function get_task_array($task) {
	$explode = explode("/",$task);
	$task_yaml = spyc_load_file($task);
	$task_yaml["Client"] = urldecode($explode[1]);
	$task_yaml["Project"] = urldecode($explode[2]);
	$task_yaml["Path"] = $task;
	return $task_yaml;
}