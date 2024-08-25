<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$username="user";
$startTime="2024-06-29 00:00:00";
$endTime="2024-08-30 00:00:00";
$page=1;
$pageSize=100;
list($ret, $err) = $client->listTrafficRechargeRecords($username, $startTime, $endTime, $page, $pageSize);
if ($err !== null) {
    var_dump($err);
    exit;
} 

var_dump($ret);