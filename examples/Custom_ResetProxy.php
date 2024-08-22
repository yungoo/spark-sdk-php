<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

$restore=false;
$accounts=['154.62.162.3:39172:2A0A154621623A39172:LTdYdgfdYuLh'];

list($ret, $err) = $client->customResetProxy($accounts, $restore);
if ($err != null) {
    var_dump($err);
    exit;
}
var_dump($ret);