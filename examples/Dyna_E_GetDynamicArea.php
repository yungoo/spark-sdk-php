<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$proxyType = 104;
$productId = ""; // sku

list($ret, $err) = $client->getDynamicArea($proxyType, $productId);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);