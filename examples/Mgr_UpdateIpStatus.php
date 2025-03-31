<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$ips=['154.62.162.3'];
$enabled=true; //true or false

list($ret, $err) = $client->updateIpsEnabled($ips, $enabled);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);