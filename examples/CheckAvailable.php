<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// 获取订单&实例信息
list($ret, $err) = $client->checkAvailable();
if ($err !== null) {
    var_dump($err);
} else if ($ret === true) {
    echo "\n====> Check Available Successfully： \n";
    var_dump($ret);
} else {
    echo "\n====> Check Available Failed \n";
    var_dump($ret);
}