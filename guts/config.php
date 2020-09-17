<?php

date_default_timezone_set('Pacific/Auckland');
define('BASE_PATH', '/tallyho/');
define('DATE_FORMAT', 'Y-m-d h:i A');
$default_rate = 100; // dollar per hour

if (!file_exists('./data')) {
	// no data folder, let's just rename the example folder
	if (!file_exists('./data_example')) {
		die('You need to have a read/writeable <em>data</em> folder in the root folder. i.e. '.BASE_PATH.'data');
	}
	else {
		rename('./data_example', './data');
	}
}