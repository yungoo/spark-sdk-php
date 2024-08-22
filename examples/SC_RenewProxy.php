<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// 已生效、未过期的实例，可以删除（删除后账号密码会失效）
$reqOrderId = generate_order_id();  // 本次请求的新ID
list($ret, $err) = $client->renewProxy($reqOrderId, [["instanceId" => "f2d8469daf5b4a9abe1a359b950d7608", "duration" => 30, "unit" => 1]]);
var_dump($ret);
var_dump($err);