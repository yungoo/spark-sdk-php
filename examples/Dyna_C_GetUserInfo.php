<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username = "yd_200005";   # 客户方唯一用户ID

list($ret, $err) = $client->getDynamicUserInfo($username);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);