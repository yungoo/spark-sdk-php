<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// get all instances of a specific order
list($ret, $err) = $client->initProxyUser("1", '黄海洋');
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
