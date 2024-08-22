<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username = "yd_200005";   # 客户方唯一用户ID
$password = "1234";
$status = 2;  // 1-可用 2-禁用

// create proxy user
list($ret, $err) = $client->createDynamicUser($username, $password, $status);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret['data']);