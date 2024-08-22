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

    public function getProductStock(int $proxyType, String $countryCode = null, String $areaCode = null, 
        String $cityCode = null, String $productId="", int $page=1, $pageSize=100)
    {
        return $this->post('GetProductStock', array(
            "proxyType" => $proxyType,
            "countryCode" => $countryCode ?? '',
            "areaCode" => $areaCode ?? '',
            "cityCode" => $cityCode ?? '',
            "productId" => $productId ?? '',
            "page" => $page,
            "pageSize" => $pageSize
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
        return array($ret, $err);
    }

    /**
     * get flow balance
     * 
     * @param String $userId customer userId unique
     * @param String $name user nick name, optional
     * 
     * @return Array 
     *
     */
    public function initProxyUser(String $userId, String $name)
    {
        list($ret, $err) = $this->post('InitProxyUser', array(
            "reqUserId" => $userId,
            "name" => $name
        ));
        return array($ret, $err);
    }

    /**
     * get flow balance
     * 
     * @param String $userId username 
     *
     * @return Array 
     *
     */
    public function getProxyUser(String $userId)
    {
        list($ret, $err) = $this->post('GetProxyUser', array(
            "reqUserId" => $userId
        ));
        return array($ret, $err);
    }

    /**
     * recharge flow, will create a new order for proxy ips
     * 
     * @param String $userId userId of customer
     * @param String $reqOrderNo order number
     * @param int $traffic flow in MB
     * @param int $validityDays unit of duration, in days
     */
    public function rechargeTraffic(String $userId, String $reqOrderNo, int $traffic, int $validityDays)
    {
        return $this->post('RechargeTraffic', array(
            "reqUserId" => $userId,
            "reqOrderNo" => $reqOrderNo,
            "traffic" => $traffic,
            "validityDays" => $validityDays,
        ));
    }

    /**
     * recharge flow, will create a new order for proxy ips
     * 
     * @param String $reqOrderNo order number
     */
    public function getTrafficRecord(String $reqOrderNo)
    {
        return $this->post('RechargeTraffic', array(
            "reqOrderNo" => $reqOrderNo,
        ));
    }

    /**
     * list traffic usages
     * 
     * @param String $userId user id
     * @param String $startTime start time
     * @param String $endTime end time
     * @param String $type days | hours
     * 
     * @return Array [{"usage": 1, "date": "2024-06-29"}]
     */
    public function listTrafficUsages(String $userId, String $startTime, String $endTime, String $type)
    {
        return $this->post('ListTrafficUsage', array(
            "reqUserId" => $userId,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "type" => $type,
        ));
    }

    /**
     * get flow endpoint
     * 
     * @param String $area_code country/area code of flow endpoint
     * 
     */
    public function getProxyEndpoints(String $area_code)
    {
        return $this->post('GetProxyEndpoints', array(
            "countryCode" => $area_code,
        ));
    }

    /**
     * direct create proxy instance
     *
     * @param String $reqOrderNo order number
     */
    public function customCreateProxy(String $reqOrderNo, Array $ips)
    {
        return $this->post('CustomCreateProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "ips" => $ips
        ));
    }

    /**
     * direct delete proxy instances
     *
     * @param String $reqOrderNo order number
     */
    public function customDelProxy(String $reqOrderNo, Array $accounts)
    {
        return $this->post('CustomDelProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "ips" => $accounts
        ));
    }
}
