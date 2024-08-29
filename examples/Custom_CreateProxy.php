<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$ips=['154.62.162.3'];
$shareable=true;
list($ret, $err) = $client->customCreateProxy(generate_order_id(), $ips, $shareable);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
