<?php

define("BR", "<br>");
define("NL", '
');

function scandir_clean($dir)
{
	$contents = array_diff(scandir($dir), array('..', '.'));
	return $contents;
}

function find_all_files($dir, &$results = array(), &$info_arr = array())
{
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$path = $dir . DIRECTORY_SEPARATOR . $value;
			if (!is_dir($path)) {
				if (substr($value, -4, 4) == '.txt') {
					$results[] = $path;
				}
				if (substr($value, -10, 10) == '_info.yaml') {
					$info_arr[] = $path;
				}
			} else if ($value != "." && $value != "..") {
				find_all_files($path, $results, $info_arr);
			}
		}
		return array($results, $info_arr);
	} else {
		die('Data folder missing. Quitting.');
	}
}


function sort_tasks_by_time($array)
{
	// takes the result of find_all_files()
	usort($array, "cmp");
	return $array;
}

function cmp($a, $b)
{
	// sorting companion for sort_tasks_by_time()
	if (substr($a, -17, 14) == substr($b, -17, 14)) {
		return 0;
	}
	return (substr($a, -17, 14) > substr($b, -17, 14)) ? -1 : 1;
}

function get_task_array($in)
{
	// takes a path to the task (e.g. 20203112-2359.txt) and returns an array.
	$explode = explode("/", $in);
	$task_yaml = spyc_load_file($in);
	$date = substr($explode[3], 0, -4);
	$formatted = DateTime::createFromFormat('Ymd-Hi', $date);
	$task_yaml["Date"] = date_format($formatted, 'Y-m-d h:i A');
	$task_yaml["Client"] = urldecode($explode[1]);
	$task_yaml["Project"] = urldecode($explode[2]);
	$task_yaml["Path"] = $in;
	return $task_yaml;
}

function get_info_array($info_array)
{
	// takes array w/ all _info.yaml files and creates array with its data, including Rate/Desc/Invoiced etc. 
	// the main use is to easily find which clients/projects have outstanding invoices

	$a = 0;
	foreach ($info_array as $info) {
		$file_data = spyc_load_file($info);
		$explode = explode("/", $info);
		foreach ($file_data as $data => $value) {
			if (sizeof($explode) == 4) {
				$info_array_data[urldecode($explode[1])][urldecode($explode[2])]['Path'] =  $info;
				$info_array_data[urldecode($explode[1])][urldecode($explode[2])][$data] = $value;
			} else {
				$info_array_data[$explode[1]]['Path'] = $info;
				$info_array_data[$explode[1]][$data] = $value;
			}
		}
		$a++;
	}
	return $info_array_data;
}

function clients_and_projects($array)
{
	// returns an multi-dim array with clients and their projects
	$new = array();
	foreach ($array as $path) {
		$explode = explode("/", $path);
		if (@!in_array(urldecode($explode[2]), $new[urldecode($explode[1])])) {
			$new[urldecode($explode[1])][] = urldecode($explode[2]);
		}
	}
	return $new;
}

function get_last_tasks($arr, $max)
{
	//returns just clients and projects, stripping dupes
	$new = array();
	$i = 0;
	$prev = array();
	foreach ($arr as $task) {
		$task_parts = explode("/", $task);
		$client = urldecode($task_parts[1]);
		$project = urldecode($task_parts[2]);
		$new[$i] = array($client, $project);
		$new = super_unique($new);
		$i++;
		if (sizeof($new) >= $max) {
			break;
		}
	}
	return $new;
}

function log_change($type, $new, $old)
{
	$filename = 'cache/history/' . $type . '.txt';
	$fileContents = @file_get_contents($filename);
	$fp = fopen($filename, 'w+');
	$content = date('Y-m-d H:i:s') . "\n\r# NEW RECORD\n\r" . $old . "\n\r\n\r# OLD RECORD\n\r" . $new . "\n\r----\n\r";
	fwrite($fp, $content . $fileContents);
	fclose($fp);
}

function format_date($date, $format = 'D d M Y')
{ // can be extended to allow both in_format and out_format
	$formatted = DateTime::createFromFormat(DATE_FORMAT, $date);
	$dateout = date_format($formatted, $format);
	return $dateout;
}
function format_time($date, $format = 'g:ia')
{
	$formatted = DateTime::createFromFormat(DATE_FORMAT, $date);
	$time_out = date_format($formatted, $format);
	return $time_out;
}

function get_starttime($date, $duration, $format = 'g:ia')
{
	$formatted = DateTime::createFromFormat(DATE_FORMAT, $date);
	$time_out = date_format($formatted->modify("-" . $duration . " minutes"), $format);
	return $time_out;
}
function format_datetime($date, $format = 'Y-m-d h:i A')
{
	$formatted = DateTime::createFromFormat(DATE_FORMAT, $date);
	$datetime = date_format($formatted, $format);
	return $datetime;
}
function calculate_cost($mins, $path, $is_expense = "")
{
	if ($is_expense) {
		$cost['raw'] = $mins;
		$cost['formatted'] = number_format($cost['raw'], 2, '.', ',');
		$cost['source'] = 'Expense';
		return $cost;
	}
	global $default_hourlyrate;
	// search for yaml in project folder first, then client folder, else use default
	$pieces = explode("/", $path);
	$project_yaml = $pieces[0] . '/' . $pieces[1] . '/' . $pieces[2] . '/_info.yaml';
	$client_yaml = $pieces[0] . '/' . $pieces[1] . '/_info.yaml';
	if (@spyc_load_file($project_yaml)['Rate']) {
		$yaml = spyc_load_file($project_yaml);
		$rate = $yaml['Rate'];
		$cost['source'] = CURRENCY_SYMBOL . $rate . '/hr (Project Rate)';
	} elseif (@spyc_load_file($client_yaml)['Rate']) {
		$yaml = spyc_load_file($client_yaml);
		$rate = $yaml['Rate'];
		$cost['source'] = CURRENCY_SYMBOL . $rate . '/hr (Client Rate)';
	} else {
		$rate = $default_hourlyrate;
		$cost['source'] = CURRENCY_SYMBOL . $rate . '/hr (Default Rate)';
	}

	$cost['raw'] = $rate * ($mins / 60);
	$cost['formatted'] = number_format($cost['raw'], 2, '.', ',');
	return $cost;
}
function timeheat($time)
{
	$val = intval($time / 30);
	if ($val < 0) {
		$val = 0;
	}
	if ($val > 4) {
		$val = 4;
	}
	return $val;
}

function output_dropdown($array, $inputname, $class, $initialvalue, $placeholder)
{
	sort($array);
	$output = '<div class="field has-addons dropdown-container">
		<div class="control">
			<div class="dropdown">
				<div class="dropdown-trigger">
					<button class="button ' . $class . '" aria-haspopup="true" aria-controls="add-dropdown-menu">
						<span class="icon has-text-link">
							<i class="fas fa-angle-down" aria-hidden="true"></i>
						</span>
					</button>
				</div>
				<div class="dropdown-menu" id="add-dropdown-menu" role="menu">
					<div class="dropdown-content">';
	foreach ($array as $field) {
		$output .= '<a href="" class="dropdown-item dropdown-selection" data-value="' . $field . '">' . $field . '</a>';
	}
	$output .= '</div>
				</div>
			</div>
		</div>
		<div class="control is-expanded">
			<input name="' . $inputname . '" class="input ' . $class . '" type="text" value="' . $initialvalue . '" placeholder="' . $placeholder . '">
		</div>
	</div>';
	return $output;
}

function zip_backup($source, $destination)
{
	if (extension_loaded('zip') === true) {
		if (file_exists($source) === true) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
				$source = realpath($source);
				if (is_dir($source) === true) {
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file) === true) {
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
						} else if (is_file($file) === true) {
							$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source) === true) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
		}
	}
	return false;
}
function super_unique($array)
{
	$result = array_map("unserialize", array_unique(array_map("serialize", $array)));

	foreach ($result as $key => $value) {
		if (is_array($value)) {
			$result[$key] = super_unique($value);
		}
	}

	return $result;
}

function in_multiarray($elem, $array)
{
	$top = sizeof($array) - 1;
	$bottom = 0;
	while ($bottom <= $top) {
		if ($array[$bottom] == $elem)
			return true;
		else
                if (is_array($array[$bottom]))
			if (in_multiarray($elem, ($array[$bottom])))
				return true;
		$bottom++;
	}
	return false;
}
