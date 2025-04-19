<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$serverId = 2;
$productIds=[3];

list($ret, $err) = $client->bindCidrToProducts($serverId, $productIds);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);


$serverId = 2;
list($ret, $err) = $client->syncCidrBoundProductsInventory($serverId);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);
