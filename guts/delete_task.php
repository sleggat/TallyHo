<?php

$path = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$path = $_POST['Path'];
	@unlink($path);

	// log_change("Delete",spyc_dump($current),$content.NL.'Client: '.urldecode($client).NL.'Project: '.urldecode($project));

}
else {
	//echo "nothing submitted yet";
}