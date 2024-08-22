<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$ips=['154.62.162.3'];

list($ret, $err) = $client->customCreateProxy(generate_order_id(), $ips);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
