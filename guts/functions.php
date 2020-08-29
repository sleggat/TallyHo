<?php

define("BR", "<br>");
define("NL", '
');


function scandir_clean($dir) {
	$contents = array_diff(scandir($dir), array('..', '.'));
	return $contents;
}

function find_all_files($dir) {

    // fails if there's an empty client folder, or empty project folder
    $root = scandir($dir);
    foreach($root as $value)
    {
        
        if($value === '.' || $value === '..' || $value === '.DS_Store') {continue;}

        if (is_file("$dir/$value")) {
            if(substr($value, -4, 4) != '.txt') {continue;} // skip if not a .txt file
            $result[]="$dir/$value";
            continue;
        }
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

function log_change($type,$new,$old) {
    $filename = 'cache/history/'.$type.'.txt';
    $fileContents = @file_get_contents($filename);
    $fp = fopen($filename, 'w+');
    $content = date('Y-m-d H:i:s')."\n\r# NEW RECORD\n\r".$old."\n\r\n\r# OLD RECORD\n\r".$new."\n\r----\n\r";
    fwrite($fp, $content . $fileContents);
    fclose($fp);
}

function format_date($date) {
    $formatted_date = DateTime::createFromFormat('Ymd', $date);
    $pretty_date = date_format($formatted_date, 'D d M ’y');
    return $pretty_date;
}
function format_time($time) {
    //echo $time;
    $formatted_time = DateTime::createFromFormat('Hi', $time);
    $pretty_time = date_format($formatted_time, 'g:ia');
    return $pretty_time;
}

function calculate_cost($mins, $affects) {
    global $default_rate;
    // search for yaml in project folder first, then client folder, else use default
    $pieces = explode("/", $affects);
    $project_yaml = $pieces[0].'/'.$pieces[1].'/'.$pieces[2].'/_info.yaml';
    $client_yaml = $pieces[0].'/'.$pieces[1].'/_info.yaml';
    if (is_file($project_yaml)) {
        $yaml = spyc_load_file($project_yaml);
        $rate = $yaml['Rate'];
    }
    elseif (is_file($client_yaml)) {
        $yaml = spyc_load_file($client_yaml);
        $rate = $yaml['Rate'];
    }
    else {
        $rate = $default_rate;
    }

    $cost['raw'] = $rate * ($mins / 60);
    $cost['formatted'] = number_format($cost['raw'], 2, '.', ',');
    return $cost;
}