<?php
require_once __DIR__ . '/../autoload.php';

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Open\SparkProxyClient;

$supplierNo = 'test0001';
$secretKey = 'qwertyuiop123456op123456op123456';

$auth = new Auth($supplierNo, $secretKey);
$client = new SparkProxyClient($auth, Config::DEV_API_HOST);


function generate_order_id() {
  // 获取当前时间的毫秒级时间戳
  $milliseconds = round(microtime(true) * 1000);
  
  // 生成一个在1000到9999之间的随机数
  $random_num = random_int(1000, 9999);
  
  // 组合时间戳和随机数来生成订单ID
  $order_id = $milliseconds . $random_num;
  
  return $order_id;
}
