<?php
require_once __DIR__ . '/../autoload.php';
require 'initialize_client.php';

# 已生效、未过期的实例，可以删除（删除后账号密码会失效）
list($ret, $err) = $client->deleteProxy("test008", ["3bc65b4d4a284cc4a77c4a7af0225c72"]);
var_dump($ret);
var_dump($err);