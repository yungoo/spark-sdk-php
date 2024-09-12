<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    if (count($ret['data']["products"]) > 1) {
        // var_dump($ret['data']["products"]);
        $product = $ret['data']["products"][1];
        $cidrBlocks = $product['cidrBlocks'];
        $rules = [];

        var_dump($product);

        // # CASE-1.1: 从指定IP段抽取指定数量段IP
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("cidr" => $cidrBlocks[0]["cidr"], "count" => 2);
        // }

        # CASE-1.2: 从指定IP段之外抽取指定数量段IP
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("exclude" => true, "cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        // } 

        // # CASE-1.3: 从指定IP段抽取指定数量段IP, 从指定IP段之外抽取指定数量段IP
        // if (count($cidrBlocks) > 1) {
        //     $rules[] = array("exclude" => false, "cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        //     $rules[] = array("exclude" => true, "cidr" => $cidrBlocks[1]["cidr"], "count" => 1);
        // }

        // # CASE-2: 从指定IP段抽取指定数量段IP，数量不对： order item quantity is inconsistent with cidr rules
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        // }

        // # CASE-3.1: 多条相同段规则：cidr blocks is conflict
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        //     $rules[] = array("cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        // }

        // # CASE-3.2: 排除规则与包含规则冲突：cidr blocks is conflict
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        //     $rules[] = array('exclude'=>True, "cidr" => $cidrBlocks[0]["cidr"], "count" => 1);
        // }

        // # CASE-4: IP段不属于该产品：cidr block is invalid or stock is not enough
        // if (count($cidrBlocks) > 0) {
        //     $rules[] = array("cidr" => "192.168.1.0/24", "count" => 2);
        // }
        
        list($ret, $err) = $client->createProxy(generate_order_id(),  $product["productId"], 2, $product["duration"], $product["unit"], $rules);
        if ($err !== null) {
            var_dump($err);
            exit;
        } 
        var_dump($ret);
    }
}