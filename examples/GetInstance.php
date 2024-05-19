<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// get all instances of a specific order
list($ret, $err) = $client->getOrder("test_usa_240517_002.renew");
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);

// get instance detail
list($ret, $err) = $client->getInstance("f2d8469daf5b4a9abe1a359b950d7608");
if ($err !== null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
