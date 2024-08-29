<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$countryCode = "USA";
$stateCode = "";
$cityCode = "";
$cidr = "";
$page=1;
$pageSize=20;

list($ret, $err) = $client->customListCidrInfo($cidr, $countryCode, $stateCode, $cityCode, $page, $pageSize);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);

if (count($ret['data']['list']) > 0) {
    $cidr = $ret['data']['list'][0]['cidr'];
    list($ret, $err) = $client->customListCidrIps($cidr, $page, $pageSize);
    if ($err != null) {
        var_dump($err);
        exit;
    }
    var_dump($ret);
}