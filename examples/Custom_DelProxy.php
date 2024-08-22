<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$accounts=['154.62.162.3:39172:2A0A154621623A39172:LTdYdgfdYuLh'];

list($ret, $err) = $client->customDelProxy(generate_order_id(), $accounts);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);