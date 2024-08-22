<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    if (count($ret['data']["products"]) > 1) {
        $product = $ret['data']["products"][1];
        list($ret, $err) = $client->createProxy(generate_order_id(),  $product["duration"], $product["unit"], 
            array([
                    "productId" => $product["productId"], 
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
    }
}