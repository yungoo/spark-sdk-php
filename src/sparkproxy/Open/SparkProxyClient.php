<?php

namespace SparkProxy\Open;

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Http\Error;
use SparkProxy\Http\Client;
use SparkProxy\Http\Proxy;

class SparkProxyClient
{
    use StaticProxyTrait;
    use CustomProxyTrait;
    use SuperResiProxyTrait;
    use MgrProxyTrait;

    private $auth;
    private $baseURL;
    private $proxy;

    public function __construct(Auth $auth, $host = null, $proxy = null, $proxy_auth = null, $proxy_user_password = null)
    {
        $this->auth = $auth;
        $this->baseURL = sprintf("%s", $host ?? Config::API_HOST);
        $this->proxy = new Proxy($proxy, $proxy_auth, $proxy_user_password);
    }

    private function requestParams($method, $version, $args)
    {
        $baseParams = array(
            "method" => $method,
            "version" => $version ?: "2024-04-08",
            "reqId" => "",
            "timestamp" => time(),
            "supplierNo" => $this->auth->getSupplierNo()
        );
        $baseParams["params"] = $this->auth->encryptParams($args);
        return $baseParams;
    }

    private function post($method, $data = null, $version = null)
    {
        $url = sprintf('%s/v2/open/api', $this->baseURL);
        $req = $this->requestParams($method, $version, $data);
        $body = json_encode($req);
        list($ret, $err) = $this->_post($url, $body);
        if ($ret !== null && isset($ret['code']) && $ret['code'] != 200) {
            return array(null, $ret);
        }
        return array($ret, $err);
    }

    private function _post($url, $body, $contentType = 'application/json')
    {
        $headers = array();
        $headers['Content-Type'] = $contentType;
        $ret = Client::post($url, $body, $headers, $this->proxy->makeReqOpt());
        if (!$ret->ok()) {
            return array(null, new Error($url, $ret));
        }

        if ($ret->body === null) {
            return array(null, null);
        }
        $r = $ret->json();
        if (isset($r['data'])) {
            $r['data'] = $this->auth->decryptParams($r['data']);
        }
        return array($r, null);
    }

}
