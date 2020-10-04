<?php

class Config {
  public static $defaultTimezone = 'Pacific/Auckland';
  public static $defaultDateFormat = 'Y-m-d h:i A';
  public static $defaultCurrencySymbol = '$'; // This is just a string, so could be EUR or €, USD or US$ or just $
  public static $defaultHourlyRate = 75;

  public static $defaultTimeIncrement = 15;
}


date_default_timezone_set(Config::$defaultTimezone);
define('DATE_FORMAT', Config::$defaultDateFormat);
define('CURRENCY_SYMBOL', Config::$defaultCurrencySymbol); 
$default_hourlyrate = Config::$defaultHourlyRate; // dollar per hour
$default_timeincrement = Config::$defaultTimeIncrement; // dollar per hour

if (!file_exists('./data')) {
	// no data folder, let's just rename the example folder
	if (!file_exists('./data_example')) {
		die('You need to have a read/writeable <em>data</em> folder in the root folder. i.e. /tallyho/data');
	}
	else {
		rename('./data_example', './data');
	}
}