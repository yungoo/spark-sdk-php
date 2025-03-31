<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$countryCode = "";
$stateCode = "";
$cityCode = "";
$sku = "";
$isp = "";
$netType = 0;
$pageNo=1;
$pageSize=100;
list($ret, $err) = $client->getProductStock(103,$countryCode, $stateCode, $cityCode, $sku, $isp, $netType, $pageNo, $pageSize);
if ($err != null) {
    var_dump($err);
}
var_dump($ret);