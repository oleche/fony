<?php
// core-router.php

$_SERVER['HTTP_Authorization'] = $_SERVER["HTTP_AUTHORIZATION"] ?? '';
$_REQUEST['request'] = ltrim($_SERVER[ 'REQUEST_URI' ], '{PROJECTBASEPATH}');
require_once $_SERVER['DOCUMENT_ROOT'] . '/api.php' ;
return;

?>