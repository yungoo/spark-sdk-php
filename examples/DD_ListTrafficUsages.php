<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->listTrafficUsages("user", "2024-06-29 00:00:00", "2024-06-30 00:00:00");
if ($err !== null) {
    var_dump($err);
    exit;
} 

var_dump($ret);