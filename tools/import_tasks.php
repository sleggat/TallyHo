<?php

$br ="
";

// import Projects (and Clients) from Kimai CSV
$source = file_get_contents('./kimai-export-2.csv');
$array = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $source));

foreach ($array as $part) {
	$client = urlencode(trim($part[8]));
	$project = urlencode(trim($part[9]));
	$description = addslashes(trim($part[11]));
	$rate = trim($part[14]);
	$status = trim($part[12]);
	$duration = trim($part[3])/60;

	$date = str_replace('-','',trim($part[0]));
	$time = str_replace(':','',trim($part[1]));



	//$date = DateTime::createFromFormat('Y-d-m', '2009-08-12');
	//$newdate = $date->format('Y-m-d');

	$folder = "../data/".$client."/".$project;
	$file = $folder.'/'.$date.'-'.$time.'.txt';
	echo "<br>Making ".$file;
	$content = "";
	$content .= "Date: ".$date.$br;
	$content .= "Time: ".$time.$br;
	$content .= "Duration: ".$duration.$br;
	$content .= "Description: \"".$description."\"";
// consider making the above a function as it's also used in the update code


	echo $content;

	@mkdir($folder, 0777, true);


	$fp = fopen($file, 'w');
	fwrite($fp, $content);
	fclose($fp);

}