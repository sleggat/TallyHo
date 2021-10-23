<?php

require_once __DIR__ . "/guts/config.php";
require_once __DIR__ . "/guts/vendor/mustangostang/spyc/Spyc.php";
require_once __DIR__ . "/guts/functions.php";

$file_structure = find_all_files_cached(DATA_PATH); // returns an array with [0] tasks, [1] _info.yaml
$all_records_array = $file_structure[0];
$info_array = get_info_array($file_structure[1]);

$all_records_sorted_array = sort_tasks_by_time($all_records_array);

// Show $all_records_sorted_array as JSON
// echo json_encode($all_records_sorted_array);

$clients_and_projects = clients_and_projects($all_records_array);

// function to create Client wih All Projects
function export($arr)
{
    //returns just clients and projects, stripping dupes
    $new = array();
    $i = 0;
    foreach ($arr[0] as $task) {
        $task_parts = explode("/", $task);
        $client = urldecode($task_parts[1]);
        $project = urldecode($task_parts[2]);
        $new[$client]['Projects'][] = $project;
        // $new[$client]['Projects'][$project]['Info'] = '';
        $url = './' . $task_parts[0] . '/' . $task_parts[1]  . '/' . '_info.yaml';
        // echo $url;
        if (file_exists($url)) {
            $file_data = spyc_load_file($url);
            // print_r($file_data);
            // $new[$client]['Projects'][$task_parts[2]] = $file_data;
        }


        // print_r(array_push($client, $project));
        $new = super_unique($new);
        // array_push($new[$i], $project);
        $i++;
    }
    // return $new;

    $a = 0;
    foreach ($arr[1] as $info) {
        $source = $info;
        $file_data = spyc_load_file($info);
        foreach ($file_data as $data => $value) {
            $exploded = explode("/", $info);
            $new[urldecode($exploded[1])][$data] = $value;
        }
        $a++;
    }
    return $new;
}
print_r(json_encode(export($file_structure)));
