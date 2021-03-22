<?php

require 'vendor/autoload.php';
define('MY_DOC_ROOT', __DIR__);
define('MY_ASSET_ROOT', __DIR__);

use {PROJECTNAMESPACE}\router;
use Geekcow\FonyCore\FonyApi;

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $router = new Router('{PROJECTCONFIGFILE}');
    $API = new FonyApi($_REQUEST['request'], $router, $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (\Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}

?>
