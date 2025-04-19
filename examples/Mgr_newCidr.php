<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// 1. 先查询IP段是否存在
$countryCode = "USA";
$stateCode = "USA0CA";
$cityCode = "";
$cidr = "192.168.1.0/24";
$page = 1;
$pageSize = 20;

// 查询IP段列表
list($ret, $err) = $client->listCidrInfo($cidr, "", "", "", $page, $pageSize);
if ($err != null) {
    var_dump($err);
    exit;
}

var_dump($ret);

// 如果不存在则创建
if (empty($ret['data']['list'])) {
    // 创建新IP段
    $address = "192.168.1.2";
    $port = 53400;
    $netType = 1; // 1-原生IP 2-广播IP
    $asn = "AS147";
    $isp = "Cogent";
    
    list($ret, $err) = $client->addCidr($countryCode, $stateCode, $cityCode, $address, $port, $netType, $asn, $isp);
    if ($err != null) {
        var_dump($err);
        exit;
    }
    var_dump($ret);
    
    $serverId = $ret['data']['id'];
    echo "成功创建新IP段，ID: $serverId\n";
    
    // 保存IP列表
    $ips = [
        ["ip" => "192.168.1.1", "enabled" => true],
        ["ip" => "192.168.1.2", "enabled" => true],
        ["ip" => "192.168.1.3", "enabled" => true]
    ];
    
    list($ret, $err) = $client->saveCidrIps($serverId, $ips);
    if ($err != null) {
        var_dump($err);
        exit;
    }
    echo "成功保存IP列表\n";
    var_dump($ret);
} else {
    echo "IP段已存在:\n";
    var_dump($ret['data']['list'][0]);
}
