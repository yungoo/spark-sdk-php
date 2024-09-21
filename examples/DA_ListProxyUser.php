<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

// get all instances of a specific order
$name="";
$page=1;
$pageSize=100;
list($ret, $err) = $client->ListProxyUser($name, $page, $pageSize);
if ($err != null) {
    var_dump($err);
    exit;
}
// var_dump($ret);

foreach ($ret['data']['list'] as $user) {
    if ($user['username'] == 'sp66e416f7272d1294b3002cba') {
        var_dump($user);   
    }    
}
