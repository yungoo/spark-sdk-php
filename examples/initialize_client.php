<?php
require_once __DIR__ . '/../autoload.php';

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Open\SparkProxyClient;

$supplierNo = 'test0001';
// $privateKey = file_get_contents('./key.pem');
$privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDVHPJ2NeA6R5Klf9WkVs+hsYUPQSxLGFpsrKh6uv5831RlVyTr
zGkd/7ZyZVaadQtalkt+hrfDmDI/nYXEbQq0skhgvW8oMh2PZUVr6GeUeHvG8KfS
28ySpsDP+ELeu1865iMxNPi2mJBNxQyXyO3iUFdMP8pV8xJdQdtkSPDBsQIDAQAB
AoGBAJLfu16q7NldoHy9KJF1Xu3SOaD0ysEKjK9fI1JKc7+97x1UvNZh74RESwp2
OwSCbAvHj0opMJb12pOrTZi9ieUTBN5d7CnRVuELl2t6dKCyy69eBwd2UITY19dS
JF6QE+h34ZJsvJDp44QUnyaKOiOU4UVYeAVLheXzwCs/gwrNAkEA5Y4FKP0yD6yv
jjdMw1ZCMGZV5puZsQyCblbNaL8jPAYQGd1l4CZYB64MsjSmntySQxDSzuw++uNM
SW56/OXy6wJBAO2qCDlR7xNAtmH4vftBWwk4KuazZNdu0cUXjwzA1WQvboHgSubT
ZD+MMwcjWhjLOCxOwPtmMAYjE3pDk15WHtMCQCmGMjrC6l5Zf3w7VqBzJw/4Qwuv
E/Mp7yIkg42yHZ6K/jifiwEsDnp9KoDF82oDPlXxYiDaLV5W5YLXAFplAjcCQQC9
jmM2zJnBoliVNZ7ZelwQs2LMVIL2rOXUrCClTFwmpwodvnYfOrV3VewRImom4lcw
R7P2D5/4FRvg5Wrx0ACPAkBtqIj3tH3qDwyZruct++jh8mvInlOz9X+EK9sm2DOw
bhVFzej/rM1tuId+1LIIqzYzDSSJE4BaOeqyRkfa9jN+
-----END RSA PRIVATE KEY-----';


$auth = new Auth($supplierNo, $privateKey);
$client = new SparkProxyClient($auth, Config::SANDBOX_API_HOST);