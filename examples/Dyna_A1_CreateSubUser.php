<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

# 创建代理子账号
$mainUsername = "yd_200005";   # 客户方唯一用户ID
$username = "yd_200005_01";   # 客户方唯一用户ID
$password = "1234";
$usageLimit = 5 * 1024;  # MB
$status = 1;  # 1-可用 2-禁用
$remark = '测试用户1';

# Case-1: 正常，重复创建返回"子账号已存在"(10061)
list($ret, $info) = $client->createDynamicSubUser($mainUsername, $username, $password, $status, $usageLimit, $remark);
var_dump($ret);
var_dump($info);

# Case-2: 主账号不存在 10052
list($ret, $info) = $client->createDynamicSubUser("yd_0001", $username, $password, $status, $usageLimit, $remark);
var_dump($ret);
var_dump($info);
