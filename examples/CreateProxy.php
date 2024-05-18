<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    if (count($ret['data']) > 1) {
        $product = $ret['data'][1];
        list($ret, $err) = $client->createProxy("test_240518_03", $product["productId"], 2, 
            $product["duration"] * 2, $product["unit"], $product["countryCode"], $product["areaCode"], $product["cityCode"]);
        if ($err !== null) {
            var_dump($err);
            exit;
        } 
    
        list($ret, $err) = $client->getOrder($ret['data']["reqOrderNo"]);
        var_dump($ret);
        var_dump($err);
    }
}