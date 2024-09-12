<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$reqOrderId = generate_order_id();  // 本次请求的新ID
// 已生效、未过期的实例，过期7天内的可以续费（账号密码会恢复）
$accounts = [
    ["account" => "154.62.162.121:36277:2A4KA15462162121A36277:dgtRkzgwrLHp", "duration" => 30, "unit" => 1],
    ["account" => "154.62.162.206:33140:2A4LA15462162206A33140:ndaxwxgn5ttR", "duration" => 60, "unit" => 1]
];
list($ret, $err) = $client->customRenewProxy($reqOrderId, $accounts);
var_dump($ret);
var_dump($err);