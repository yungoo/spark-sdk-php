<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

function generateOrderNumber() {
    // 获取当前时间
    $currentDateTime = new DateTime();

    // 格式化时间为所需格式
    $formattedDate = $currentDateTime->format('ymd_His'); // 格式化为 240519_0916

    // 生成订单号
    $orderNumber = 'test_' . $formattedDate;

    return $orderNumber;
}

list($ret, $err) = $client->rechargeTraffic("user", generateOrderNumber(),  1000, 60);
if ($err !== null) {
    var_dump($err);
    exit;
} 

var_dump($ret);