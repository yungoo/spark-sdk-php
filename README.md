# Sparkproxy OpenApi SDK for PHP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/v/tag/sparkpoxy/spark-sdk-python.svg?label=release)](https://github.com/yungoo/spark-sdk-python/releases)
[![Latest Stable Version](https://img.shields.io/pypi/v/sparkproxy.svg)](https://pypi.python.org/pypi/sparkproxy)
[![Download Times](https://img.shields.io/pypi/dm/sparkproxy.svg)](https://pypi.python.org/pypi/sparkproxy)



## 安装

推荐使用 `composer` 进行安装。可以使用 composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`sparkproxy/php-sdk`][install-packagist] 。

```bash
$ composer require sparkproxy/php-sdk
```

## 运行环境

| sparkproxy SDK版本 |                     PHP 版本                      |
|:--------------------:|:-----------------------------------------------:|
|          0.x         | cURL extension,   5.3 - 5.6, 7.0 - 7.4, 8.0-8.1 |

## 使用方法

### 创建代理
```php
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

list($ret, $err) = $client->getProductStock(103);
if ($err == null) {
    if (count($ret['data']) > 1) {
        $product = $ret['data'][1];
        list($ret, $err) = $client->createProxy("test_240518_03", $product["productId"], 2, 
            $product["duration"] * 2, $product["unit"], $product["countryCode"], $product["areaCode"], $product["cityCode"]);
        if ($err !== null) {
            var_dump($err);
            exit;
        } 
    
        list($ret, $err) = $client->getOrder($ret['data']["reqOrderNo"]);
        var_dump($ret);
        var_dump($err);
    }
}
```

## 测试

``` bash
$ ./vendor/bin/phpunit tests/SparkProxy/Tests/
```

## 常见问题

- `$error` 保留了请求响应的信息，失败情况下 `ret` 为 `none`, 将 `$error` 可以打印出来，提交给我们。
- API 的使用 demo 可以参考 [examples](https://github.com/qiniu/php-sdk/tree/master/examples)。

## 代码贡献

详情参考[代码提交指南](https://github.com/yungoo/spark-sdk-php/blob/master/CONTRIBUTING.md)。

## 贡献记录

- [所有贡献者](https://github.com/yungoo/spark-sdk-php/contributors)

## 联系我们

- 如果需要帮助，请提交工单（在portal右侧点击咨询和建议提交工单，或者直接向 support@qiniu.com 发送邮件）
- 如果发现了bug， 欢迎提交 [issue](https://github.com/yungoo/php-sdk/issues)
- 如果有功能需求，欢迎提交 [issue](https://github.com/yungoo/php-sdk/issues)
- 如果要提交代码，欢迎提交 pull request

## 代码许可

The MIT License (MIT).详情见 [License文件](https://github.com/yungoo/spark-sdk-php/blob/master/LICENSE).

[packagist]: http://packagist.org
[install-packagist]: https://packagist.org/packages/sparkproxy/spark-sdk-php
