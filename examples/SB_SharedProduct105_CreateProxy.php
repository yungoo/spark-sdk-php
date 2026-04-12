<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// 共享商品示例（proxyType=105）
// 1) 先拉取库存并定位目标 SKU
// 2) 使用 SKU + CIDR 规则下单
// 3) 注意：同账号同IP重复开通限制仅对共享商品生效

$proxyType = 105;
$targetSku = "eafa86eaa4e24ca2a809e10c6983f32b";

list($ret, $err) = $client->getProductStock($proxyType);
if ($err !== null) {
    var_dump($err);
    exit;
}

$products = $ret['data']['products'] ?? [];
$targetProduct = null;
foreach ($products as $product) {
    if (($product['productId'] ?? '') === $targetSku) {
        $targetProduct = $product;
        break;
    }
}

if ($targetProduct === null) {
    echo "target sku not found in proxyType=105 stock: {$targetSku}\n";
    exit;
}

$cidrBlocks = $targetProduct['cidrBlocks'] ?? [];
if (count($cidrBlocks) < 1) {
    echo "no cidr blocks for target shared product\n";
    exit;
}

$cidr = $cidrBlocks[0]['cidr'] ?? null;
if (empty($cidr)) {
    echo "invalid cidr block data\n";
    exit;
}

$duration = intval($targetProduct['duration'] ?? 30);
$unit = intval($targetProduct['unit'] ?? 1);

$rules = [
    [
        "cidr" => $cidr,
        "count" => 1
    ]
];

list($ret, $err) = $client->createProxy(
    generate_order_id(),
    $targetSku,
    1,
    $duration,
    $unit,
    $rules
);

if ($err !== null) {
    var_dump($err);
    exit;
}

var_dump($ret['data']);

