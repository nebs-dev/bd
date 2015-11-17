<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$txt_file    = file_get_contents('allCountries.txt');
$rows        = explode("\n", $txt_file);
array_shift($rows);

echo 'TEST';