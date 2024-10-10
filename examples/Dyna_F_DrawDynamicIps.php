<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$subUsername = "yd_200005_02";
$region="usa";
$sessTime=30; // max 120mins
$serverId=1;
$num=100;
$format="user:pass@host:port";

list($ret, $err) = $client->drawDynamicIps($subUsername, $region, $sessTime, $serverId, $num, $format);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);