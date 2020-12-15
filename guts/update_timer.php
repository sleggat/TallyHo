<?php

// this is not yet implemented


$yaml_path = DATA_PATH . '/_info.yaml';

if (isset($_GET['timerstart'])) {
    $start_time = $_GET['timerstart'];
    $info_yaml = spyc_load_file($yaml_path);
    // update the yaml (add timer) and rewrite it
} elseif (isset($_GET['timerstop'])) {
    $stop_time = $_GET['timerstop'];
    $info_yaml = spyc_load_file($yaml_path);
    // update the yaml (remove the timer) and rewrite it
}
