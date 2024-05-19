<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    if (count($ret['data']) > 1) {
        $product = $ret['data'][1];
        list($ret, $err) = $client->createProxy("test_240519_01",  $product["duration"] * 2, $product["unit"], 
            array([
                    "productId" => $product["productId"], 
                    "countryCode" => $product["countryCode"], 
                    "areaCode" => $product["areaCode"], 
                    "cityCode" => $product["cityCode"],
                    "amount" => 2
                ]));
        if ($err !== null) {
            var_dump($err);
            exit;
        } 
    
        list($ret, $err) = $client->getOrder($ret['data']["reqOrderNo"]);
        var_dump($ret);
        var_dump($err);
        if ($err !== null) {
            var_dump($err);
            exit;
        } 
    
        list($ret, $err) = $client->getOrder($ret['data']["reqOrderNo"]);
        var_dump($ret);
        var_dump($err);
    }
}