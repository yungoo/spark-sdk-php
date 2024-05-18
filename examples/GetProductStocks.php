<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    var_dump($ret);
}