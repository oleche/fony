<?php
require 'vendor/autoload.php';
define('MY_DOC_ROOT', __DIR__);
define('MY_ASSET_ROOT', __DIR__);

use {PROJECTNAMESPACE}\router;

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
  $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
  $API = new Router($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'], {PROJECTCONFIGFILE});
  echo $API->processAPI();
} catch (Exception $e) {
  echo json_encode(Array('error' => $e->getMessage()));
}

?>
