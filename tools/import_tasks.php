<?php

$br ="
";

// import Projects (and Clients) from Kimai CSV
$source = file_get_contents('./kimai-export.csv');
// $array = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $source));
$array = array_map("str_getcsv", preg_split('/\r*\n+\"+|\r+/', $source)); // slightly modified to allow fields with linebreaks
// print_r($array);
// die;
foreach ($array as $part) {

	$duration = trim($part[3]); // get duration. If not a number then skip adding.

	if ($duration == intval($duration) && intval($duration) > 0 && $duration != "") {

	$duration = $duration/60;

	$client = urlencode(trim($part[8]));
	$project = urlencode(trim($part[9]));
	$description = addslashes(trim($part[11]));
	$rate = trim($part[14]);
	$status = trim($part[12]);


		$date = str_replace(array('-','"'),'',trim($part[0]));
		$time = str_replace(':','',trim($part[1]));

		$newdate = DateTime::createFromFormat('Ymd Hi', $date.' '.$time);
		$formatteddate = $newdate->format('Y-m-d h:i A');

		$folder = "../data/".$client."/".$project;
		$file = $folder.'/'.$date.'-'.$time.'.txt';
		echo "<br>Making ".$file;
		$content = "";
		$content .= "DateAdded: '".$formatteddate."'".$br;
		$content .= "DateUpdated: '".$formatteddate."'".$br;
		$content .= "Duration: ".$duration.$br;
		$content .= "Description: '".$description."'";
	// consider making the above a function as it's also used in the update code


		echo $content;

		@mkdir($folder, 0777, true);


		$fp = fopen($file, 'w');
		fwrite($fp, $content);
		fclose($fp);
	}

}