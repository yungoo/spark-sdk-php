# Sparkproxy OpenApi SDK for PHP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/v/tag/sparkpoxy/spark-sdk-php.svg?label=release)](https://github.com/yungoo/spark-sdk-php/releases)
[![Latest Stable Version](https://img.shields.io/pypi/v/sparkproxy.svg)](https://packagist.org/packages/sparkproxy/spark-sdk-php)
[![Total Downloads](https://img.shields.io/packagist/dt/sparkproxy/spark-sdk-php.svg)](https://packagist.org/packages/sparkproxy/spark-sdk-php)

## 安装

推荐使用 `composer` 进行安装。可以使用 composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`sparkproxy/spark-php-sdk`][install-packagist] 。

```bash
$ composer require sparkproxy/spark-sdk-php
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
$secretKey = 'qwertyuiop123456op123456';

$auth = new Auth($supplierNo, $secretKey);
$client = new SparkProxyClient($auth, Config::QA_API_HOST);

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
- API 的使用 demo 可以参考 [examples](https://github.com/yungoo/spark-php-sdk/tree/master/examples)。

## 代码贡献

详情参考[代码提交指南](https://github.com/yungoo/spark-sdk-php/blob/master/CONTRIBUTING.md)。

## 贡献记录

- [所有贡献者](https://github.com/yungoo/spark-sdk-php/contributors)

## 联系我们

- 如果需要帮助，请提交工单（在portal右侧点击咨询和建议提交工单，或者直接向 support@sparkproxy.com 发送邮件）
- 如果发现了bug， 欢迎提交 [issue](https://github.com/yungoo/spark-sdk-php/issues)
- 如果有功能需求，欢迎提交 [issue](https://github.com/yungoo/spark-sdk-php/issues)
- 如果要提交代码，欢迎提交 pull request

## 代码许可

The MIT License (MIT).详情见 [License文件](https://github.com/yungoo/spark-sdk-php/blob/master/LICENSE).

[packagist]: http://packagist.org
[install-packagist]: https://packagist.org/packages/sparkproxy/spark-sdk-php
