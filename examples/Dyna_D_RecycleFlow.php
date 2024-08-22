<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username = "yd_200005";   # 客户方唯一用户ID
$orderNo = generate_order_id();
$flow = -1;  # <0: 回收剩余流量 >0: 回收指定流量, MB

list($ret, $err) = $client->recycleFlow($orderNo, $username, $flow);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);