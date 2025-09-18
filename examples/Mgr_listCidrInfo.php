<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$countryCode = "USA";
$stateCode = "";
$cityCode = "";
$cidr = "";
$accountId = "1";
$productId = "12";
$page=1;
$pageSize=20;

list($ret, $err) = $client->listCidrInfo($cidr, $countryCode, $stateCode, $cityCode, $accountId, $productId, $page, $pageSize);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);

if (count($ret['data']['list']) > 0) {
    $cidr = $ret['data']['list'][0]['cidr'];
    list($ret, $err) = $client->listCidrIps($cidr, $page, $pageSize);
    if ($err != null) {
        var_dump($err);
        exit;
    }
    var_dump($ret);
}