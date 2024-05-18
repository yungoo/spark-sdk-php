<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// get all instances of a specific order
list($ret, $err) = $client->getOrder("test_240518_03");
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);

// get instance detail
list($ret, $err) = $client->getInstance("3007f22f8a2440b09e931d1af7cc617c");
if ($err !== null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
