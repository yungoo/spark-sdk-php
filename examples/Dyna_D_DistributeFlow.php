<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username = "yd_200005";   # 客户方唯一用户ID
$orderNo = generate_order_id();
$flow = 1024;  # 需要分配的流量，单位为MB

list($ret, $err) = $client->distributeFlow($orderNo, $username, $flow);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);