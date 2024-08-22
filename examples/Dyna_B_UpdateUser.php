<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username = "yd_200005";   # 客户方唯一用户ID
$password = "1234";
$status = 2;  // 1-可用 2-禁用

// 修改密码
list($ret, $err) = $client->updateDynamicUser($username, $password);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);


// 修改状态
list($ret, $err) = $client->updateDynamicUser($username, null, $status);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);