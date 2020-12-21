<?php

class Config
{
	public static $defaultTimezone = 'Pacific/Auckland';
	public static $defaultDateFormat = 'Y-m-d h:i A';
	public static $defaultTimeFormat = 'g:ia'; // 'g:ia' returns 01:23pm, 'H:i' returns 13:23
	public static $defaultCurrencySymbol = '$'; // This is just a string, so could be EUR or €, USD or US$ or just $
	public static $defaultDataPath = 'data';
	public static $defaultHourlyRate = 75;
	public static $defaultTimeIncrement = 15;
	public static $defaultInvoiceReminderDays = 30; // days
}

date_default_timezone_set(Config::$defaultTimezone);
define('DATE_FORMAT', Config::$defaultDateFormat);
define('TIME_FORMAT', Config::$defaultTimeFormat);
define('CURRENCY_SYMBOL', Config::$defaultCurrencySymbol);
define('DATA_PATH', Config::$defaultDataPath);
define('CACHE_FILE', "cache/tasks.txt");

$default_hourlyrate = Config::$defaultHourlyRate; // dollar per hour
$default_timeincrement = Config::$defaultTimeIncrement; // dollar per hour
$defaultInvoiceReminderDays = Config::$defaultInvoiceReminderDays;

$date_now = date_create();
$defaultInvoiceReminderDate =  date_modify($date_now, '-' . $defaultInvoiceReminderDays . ' days')->format('Ymd');


if (!file_exists('./data')) {
	// no data folder, let's just rename the example folder
	if (!file_exists('./data_example')) {
		die('You need to have a read/writeable <em>data</em> folder in the root folder. i.e. /tallyho/data');
	} else {
		rename('./data_example', './data');
	}
}
