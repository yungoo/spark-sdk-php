<?php

namespace SparkProxy\Open;

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Http\Error;
use SparkProxy\Http\Client;
use SparkProxy\Http\Proxy;
use Ramsey\Uuid\Uuid;

class SparkProxyClient
{
    private $auth;
    private $baseURL;
    private $proxy;

    public function __construct(Auth $auth, $host = null, $proxy = null, $proxy_auth = null, $proxy_user_password = null)
    {
        $this->auth = $auth;
        $this->baseURL = sprintf("%s", $host ?? Config::API_HOST);
        $this->proxy = new Proxy($proxy, $proxy_auth, $proxy_user_password);
    }

    public function checkAvailable()
    {
        $msg = "hello";
        $encryptedMsg = $this->auth->encryptUsingRemotePublicKey($msg);
        list($ret, $info) = $this->post('CheckAvailable', $encryptedMsg);
        if ($ret !== null && isset($ret['code']) && $ret['code'] == 200) {
            $receivedEncryptedMsg = $ret['data'];
            $receivedDecryptedMsg = $this->auth->decryptUsingPrivateKey($receivedEncryptedMsg);
            if ($receivedDecryptedMsg === null) {
                return array(false, 'decrypt message failed');
            }
            $receivedMsg = \SparkProxy\toString($receivedDecryptedMsg);
            return array($msg === $receivedMsg, null);
        }

        return array(false, $info);
    }

    public function getProductStock(int $proxyType, String $countryCode = null, String $areaCode = null, String $cityCode = null)
    {
        return $this->post('GetProductStock', array(
            "proxyType" => $proxyType,
            "countryCode" => $countryCode ?? '',
            "areaCode" => $areaCode ?? '',
            "cityCode" => $cityCode ?? ''
        ), "2024-04-16");
    }

    /**
     * create proxy, will create a new order for proxy ips
     * 
     * @param String $reqOrderNo order number
     * @param int $duration duration of proxy, unit: day
     * @param int $unit unit of duration, 1: day, 2: week, 3: month, according to the product destail
     * @param array items 
     *              String String $productId product id
     *              int $amount amount of proxy
     *              String $countryCode country code 
     *              String $areaCode area code, can be empty, state code
     *              String $cityCode city code, can ben empty
     */
    public function createProxy(String $reqOrderNo, int $duration, int $unit, array $items)
    {
        return $this->post('CreateProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "duration" => $duration,
            "unit" => $unit,
            "items" => $items
        ), "2024-05-19");
    }

    /**
     * renew proxy, will extends the exipration of instance
     * 
     * @param String $reqOrderNo new order number
     * @param Array $instances [{instanceId: '', duration: 30, unit: 1}] all instance must in same order & duration * unit should be equals.
     * @return Array
     */
    public function renewProxy(String $reqOrderNo, Array $instances)
    {
        list($ret, $info) = $this->post('RenewProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "instances" => $instances
        ));
        if ($ret !== null && isset($ret['code']) && $ret['code'] == 200) {
            foreach ($ret['data']['ipInfo'] as &$ipInfo) {
                $password = $ipInfo["password"];
                if (strlen($password) > 0) {
                    $ipInfo["password"] = $this->auth->decryptUsingPrivateKey($password);
                }
            }
        }
        return array($ret, $info);
    }

    /**
     * delete proxy, will disable proxy authentication
     * 
     * @param String $reqOrderNo new order number
     * @param Array $instances ['instance id'] multiple instance can be deleted at once
     * @return Array
     */
    public function deleteProxy(String $reqOrderNo, Array $instances)
    {
        return $this->post('DelProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "instanceIds" => $instances
        ));
    }

    /**
     * get proxy order
     * 
     * @param String $reqOrderNo order number
     * 
     * @return Array password will autodecrypt
     */
    public function getOrder(String $reqOrderNo)
    {
        list($ret, $info) = $this->post('GetOrder', array(
            "reqOrderNo" => $reqOrderNo
        ));
        if ($ret !== null && isset($ret['code']) && $ret['code'] == 200 && isset($ret['data'])) {
            $data = $ret['data'];
            if (isset($data['ipInfo'])) {
                foreach ($data['ipInfo'] as &$ipInfo) {
                    $password = isset($ipInfo["password"]) ? $ipInfo["password"] : '';
                    if (strlen($password) > 0) {
                        $ipInfo["password"] = $this->auth->decryptUsingPrivateKey($password);
                    }
                }
            }
        }
        return array($ret, $info);
    }

    /**
     * get proxy instance
     * 
     * @param String $instanceId instance id
     *
     * @return Array password will autodecrypt
     *
     */
    public function getInstance(String $instanceId)
    {
        list($ret, $err) = $this->post('GetInstance', array(
            "instanceId" => $instanceId
        ));
        if ($ret !== null && isset($ret['code']) && $ret['code'] == 200 && isset($ret['data'])) {
            $data = $ret['data'];
            $password = isset($data["password"]) ? $data["password"] : '';
            if (strlen($password) > 0) {
                $data["password"] = $this->auth->decryptUsingPrivateKey($password);
            }
        }
        return array($ret, $err);
    }

    private function requestParams($method, $version, $args)
    {
        $baseParams = array(
            "method" => $method,
            "version" => $version ?: "2024-04-08",
            "reqId" => (string)Uuid::uuid4(),
            "timestamp" => time(),
            "supplierNo" => $this->auth->getSupplierNo(),
            "sign" => "",
            "params" => $args
        );
        $baseParams["sign"] = $this->auth->tokenOfRequest($baseParams);
        return $baseParams;
    }

    private function post($method, $data = null, $version = null)
    {
        $url = sprintf('%s/v1/open/api', $this->baseURL);
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
        $r = ($ret->body === null) ? array() : $ret->json();
        return array($r, null);
    }

}
