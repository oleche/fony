<?php
// core-router.php

$_SERVER['HTTP_Authorization'] = $_SERVER["HTTP_AUTHORIZATION"] ?? '';
$url = strtok($_SERVER["REQUEST_URI"], '?');
$_REQUEST['request'] = preg_replace('/{PROJECTBASEPATH}/', '', $url, 1);
require_once $_SERVER['DOCUMENT_ROOT'] . '/api.php' ;
return;

?>
