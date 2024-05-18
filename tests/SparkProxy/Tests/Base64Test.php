<?php
namespace SparkProxy\Tests;

use PHPUnit\Framework\TestCase;

use SparkProxy;

class Base64Test extends TestCase
{
    public function testUrlSafe()
    {
        $a = '你好';
        $b = \SparkProxy\base64_urlSafeEncode($a);
        $this->assertEquals($a, \SparkProxy\base64_urlSafeDecode($b));
    }
}
