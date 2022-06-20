<?php

require_once __DIR__ . "/guts/config.php";
require_once __DIR__ . "/guts/vendor/mustangostang/spyc/Spyc.php";
require_once __DIR__ . "/guts/functions.php";
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
    $new_add = array();
    $file_data = array();
    $i = 0;
    $output = '';
    foreach ($arr[0] as $task) {
        $task_parts = explode("/", $task);
        $client = urldecode($task_parts[1]);
        $project = urldecode($task_parts[2]);
        $url = './' . $task_parts[0] . '/' . $task_parts[1]  . '/'   . '_info.yaml';
        if (file_exists($url)) {
            $file_data = spyc_load_file($url);
            // print_r($file_data);
        }

        if (@$new['name'] == $client) {
            echo 'Exists';
        }
        $new_array = array(
            'name' => $client,
            'projects' =>
            array(
                'name' => $project,
                'rate' => (isset($file_data['Rate']) ? $file_data['Rate'] : ''),
                'invoiced' => (isset($file_data['Invoiced']) ? $file_data['Invoiced'] : '')
            )
        );
        // print_r($new_array);
        // die;
        $output .= json_encode($new_array);
    }
    print_r($output);
    return $output;
}
export($file_structure);
// echo export($file_structure);
