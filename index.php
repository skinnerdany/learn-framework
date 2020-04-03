<?php

//echo '<pre>';print_r($_REQUEST);die();
$config = include './app/config/main.php';

if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

include './framework/core.php';
core::app()->run($config);
/**/