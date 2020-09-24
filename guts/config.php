<?php
// for consistency this should be a yaml config

date_default_timezone_set('Pacific/Auckland');
define('BASE_PATH', '/tallyho/'); // currently not necessary, works as-is
define('DATE_FORMAT', 'Y-m-d h:i A');
define('CURRENCY_SYMBOL', '$'); // This is just a string, so could be EUR or €, USD or US$ or just $
$default_rate = 70; // dollar per hour

if (!file_exists('./data')) {
	// no data folder, let's just rename the example folder
	if (!file_exists('./data_example')) {
		die('You need to have a read/writeable <em>data</em> folder in the root folder. i.e. '.BASE_PATH.'data');
	}
	else {
		rename('./data_example', './data');
	}
}