<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$mainUsername = "yd_200005";   # 客户方唯一用户ID
$username = "yd_200005_01";   # 客户方唯一子用户ID
$password = "1234"; // optional
$status = 1;  # 1-可用 2-禁用, optional
$remark = '测试用户1'; // optional

// 修改密码
list($ret, $err) = $client->updateDynamicSubUser($mainUsername, $username, $password);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);

// 修改状态
list($ret, $err) = $client->updateDynamicSubUser($mainUsername, $username, null, $status, $remark);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);

// 修改备注
list($ret, $err) = $client->updateDynamicSubUser($mainUsername, $username, null, null, $remark);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);