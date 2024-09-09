<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$ips=['154.62.162.3'];
$shareable=true;
$duration=30;
$unit=1;
list($ret, $err) = $client->customCreateProxy(generate_order_id(), $ips, $shareable, $duration, $unit);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);
