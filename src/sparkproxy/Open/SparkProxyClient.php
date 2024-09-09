<?php

namespace SparkProxy\Open;

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Http\Error;
use SparkProxy\Http\Client;
use SparkProxy\Http\Proxy;

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
     * @param String $productId product id
     * @param int $duration duration of proxy, unit: day
     * @param int $unit unit of duration, 1: day, 2: week, 3: month, according to the product destail
     * @param array $rules 
     *              bool exclue exclude the ip segment, false-not in  true-in
     *              String cidr ip段，如 154.111.102.0/24
     *              int quantity quantity of proxy
     */
    public function createProxy(String $reqOrderNo, String $productId, int $quantity, int $duration, int $unit, array $rules)
    {
        return $this->post('CreateProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "productId" => $productId,
            "amount" => $quantity,
            "duration" => $duration,
            "unit" => $unit,
            "rules" => $rules || []
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
     * @param int $page page number, start from 1
     * @param int $pageSize page size, default 100
     *  
     * @return Array password will autodecrypt
     */
    public function getOrder(String $reqOrderNo, int $page=1, int $pageSize=100)
    {
        list($ret, $info) = $this->post('GetOrder', array(
            "reqOrderNo" => $reqOrderNo,
            "page" => $page,
            "pageSize" => $pageSize
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
     * init proxy user, return old if exits
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
     * get proxy user
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
     * list proxy user 
     * 
     * @param String $userId username 
     * @param int $page page number, start from 1
     * @param int $pageSize page size, default 100
     *
     * @return Array total, page, list
     *
     */
    public function listProxyUser(String $name, int $page, int $pageSize)
    {
        list($ret, $err) = $this->post('ListProxyUser', array(
            "name" => $name,
            "page" => $page,
            "pageSize" => $pageSize
        ));
        return array($ret, $err);
    }

    /**
     * recharge flow, will create a new order
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
     * get recharge records
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
     * list user traffic recharge records
     * 
     * @param String $userId user id
     * @param String $startTime start time
     * @param String $endTime end time
     * @param int $page page number, start from 1
     * @param int $pageSize page size, default 100
     * 
     * @return Array total, page, list
     *
     */
    public function listTrafficRechargeRecords(String $userId, String $startTime, String $endTime, int $page, int $pageSize)
    {
        list($ret, $err) = $this->post('ListTrafficRechargeRecord', array(
            "reqUserId" => $userId,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "page" => $page,
            "pageSize" => $pageSize
        ));
        return array($ret, $err);
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
     * get proxy endpoints
     * 
     * @param String $area_code country/area code of proxy endpoint, no need to fill
     * 
     */
    public function getProxyEndpoints(String $area_code)
    {
        return $this->post('GetProxyEndpoints', array(
            "countryCode" => $area_code,
        ));
    }

    /**
     * list paginate ip segment (CIDR) list
     *
     * @param String $cidr fuzzy search for CIDR
     * @param String $countryCode country code
     * @param String $stateCode state code
     * @param String $cityCode city code
     * @param int $page page number, start from 1
     * @param int $pageSize page size, default 100
     *
     * @return Array total, page, list
     *          data (array)
     *              total (int): 总数
     *              page (int): 当前分页
     *              list (array): 列表
     *                  cidr (str): ip
     *                  countryName (str): 国家名称
     *                  stateName (str): 省州名称
     *                  cityName (str): 城市名称
     *                  total (int): 总IP数据
     *                  remains (int): 剩余IP数
     *                  used (int): 已用用IP数
     *                  cooldown (int): 冷却期IP数
     *                  accounts (int): 正在使用的账户数
     */
    public function customListCidrInfo($cidr, $countryCode, $stateCode, $cityCode, $page, $pageSize) 
    {
        return $this->post('CustomQueryAreaCidrList', array(
            "cidr" => $cidr,
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "page" => $page,
            "pageSize" => $pageSize
        ));
    }

    /**
     * list paginate ip detail list
     *
     * @param String $cidr fuzzy search for CIDR
     * @param int $page page number, start from 1
     * @param int $pageSize page size, default 100
     *
     * @return Array total, page, list
     *          data (array)
     *              total (int): 总数
     *              page (int): 当前分页
     *              list (array): 列表
     *                  ip (str): ip
     *                  countryCode (str): 国家代码
     *                  countryName (str): 国家名称
     *                  stateCode (str): 省洲代码
     *                  stateName (str): 省州名称
     *                  cityCode (str): 城市代码
     *                  cityName (str): 城市名称
     *                  enabled (bool): 是否有效
     *                  state (int): 状态, 0-free 1-locked 2-used 3-cooldown
     *                  autoUnlockAt (str): 自动解锁时间
     *                  accounts (int): 正在使用的账户数
     */
    public function customListCidrIps($cidr, $page, $pageSize) 
    {
        return $this->post('CustomListCidrIps', array(
            "cidr" => $cidr,
            "page" => $page,
            "pageSize" => $pageSize
        ));
    }

    /**
     * update ips status
     *
     * @param array $ips ip array
     * @param bool $enabled enabled or not
     * 
     * @return None or error
     */
    public function customUpdateIpStatus(array $ips, bool $enabled) {
        return $this->post('CustomUpdateIpStatus', array(
            "ips" => $ips,
            "enabled" => $enabled
        ));
    }

    /**
     * direct create proxy instance
     *
     * @param String $reqOrderNo order number
     * @param array $ips ip array
     * @param bool $shareable shareable or not
     * @param int $duration duration of proxy, unit: day
     * @param int $unit unit of duration, 1: day, 2: week, 3: month, according to the product destail
     */
    public function customCreateProxy(String $reqOrderNo, array $ips, bool $shareable, int $duration, int $unit)
    {
        return $this->post('CustomCreateProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "ips" => $ips,
            "shareable" => $shareable,
            "duration" => $duration,
            "unit" => $unit
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

    /**
     * direct check proxy account
     *
     * @param Array $accounts each item as "user:pass:host:port"
     */
    public function customCheckProxy(Array $accounts)
    {
        return $this->post('CustomChkProxy', array(
            "ips" => $accounts
        ));
    }

    /**
     * direct reset proxy accounts
     *
     * @param array $accounts each item as "user:pass:host:port"
     * @param bool $restore if true, will restore to original password
     *
     */
    public function customResetProxy(array $accounts, bool $restore)
    {
        return $this->post('CustomResetProxy', array(
            "ips" => $accounts,
            "restore" => $restore
        ));
    }

    /**
     * create proxy user
     * 
     * @param String $username customer userId unique
     * @param String $password user password
     * @param int $status 1: disable, 2: enable
     
     * 
     * @return array $ret, $err 
     *
     */
    public function createDynamicUser(String $username, String $password, int $status)
    {
        list($ret, $err) = $this->post('CreateUser', array(
            "username" => $username,
            "password" => $password,
            "status" => $status
        ));
        return array($ret, $err);
    }

    /**
     * create proxy sub user
     *
     * @param String $mainUsername main account id
     * @param String $username customer userId unique
     * @param String $password user password
     * @param int $status 1: disable, 2: enable
     * @param int $usageLimit traffic limit in MB
     * @param String $remark remark
     *
     * @return Array $ret, $err 
     */
    public function createDynamicSubUser($mainUsername, $username, $password, $status, $usageLimit, $remark) {
        list($ret, $err) = $this->post('CreateSubUser', array(
            "mainUsername" => $mainUsername,
            "username" => $username,
            "password" => $password,
            "status" => $status,
            "usageLimit" => $usageLimit,
            "remark" => $remark
        ));
        
        return array($ret, $err);
    }

    /**
     * update proxy user
     * 
     * @param String $username customer userId unique, required
     * @param String $password user password, optional
     * @param int $status 1: disable, 2: enable, optional
     *
     * 
     * @return Array $ret, $err 
     *
     */
    public function updateDynamicUser(String $username, String $password = null, int $status = null)
    {
        list($ret, $err) = $this->post('UpdateUser', array(
            "username" => $username,
            "password" => $password,
            "status" => $status
        ));
        return array($ret, $err);
    }

    /**
     * update proxy sub user
     *
     * @param String $mainUsername main account id
     * @param String $username customer userId unique
     * @param String $password user password, optional
     * @param int $status 1: disable, 2: enable, optional
     * @param String $remark remark, optional
     *
     *
     * @return Array $ret, $err 
     */
    public function updateDynamicSubUser($mainUsername, $username, $password=null, $status=null, $remark=null) {
        list($ret, $err) = $this->post('UpdateSubUser', array(
            "mainUsername" => $mainUsername,
            "username" => $username,
            "password" => $password,
            "status" => $status,
            "remark" => $remark
        ));
        
        return array($ret, $err);
    }

    /**
     * get proxy user info
     * 
     * @param String $username customer userId unique, required 
     * 
     * @return Array $ret, $err 
     *
     */
    public function getDynamicUserInfo(String $username)
    {
        list($ret, $err) = $this->post('GetUserInfo', array(
            "username" => $username
        ));
        return array($ret, $err);
    }

    /**
     * distribute flow to proxy user
     * 
     * @param String $reqOrderNo customer unique order no, required 
     * @param String $username customer userId unique, required 
     * @param int $flow traffic in MB, required 
     *
     * @return Array $ret, $err 
     *
     */
    public function distributeFlow(String $reqOrderNo, String $username, int $flow)
    {
        list($ret, $err) = $this->post('DistributeFlow', array(
            "reqOrderNo" => $reqOrderNo,
            "username" => $username,
            "flow" => $flow
        ));
        return array($ret, $err);
    }

    /**
     * recycle flow from proxy user
     * 
     * @param String $reqOrderNo customer unique order no, required 
     * @param String $username customer userId unique, required 
     * @param int $flow traffic in MB, required 
     *
     * @return Array $ret, $err 
     *
     */
    public function recycleFlow(String $reqOrderNo, String $username, int $flow)
    {
        list($ret, $err) = $this->post('RecycleFlow', array(
            "reqOrderNo" => $reqOrderNo,
            "username" => $username,
            "flow" => $flow
        ));
        return array($ret, $err);
    }

    /**
     * get proxy area
     * 
     * @param int $proxyType 104
     * @param String $productId sku, required 
     * 
     * @return Array $ret, $err 
     *
     */
    public function getDynamicArea(int $proxyType, String $productId)
    {
        list($ret, $err) = $this->post('GetDynamicArea', array(
            "proxyType" => $proxyType,
            "productId" => $productId
        ));
        return array($ret, $err);
    }

    /**
     * draw dynamic ips
     * 
     * @param String $subUsername sub proxy user, required
     * @param String $region region, optional
     * @param int $sessTime session time, optional
     * @param int $num number of ips, optinal
     * @param String $format format, ['user:pass:host:port', 'user:pass@host:port', 'host:port:user:pass'], optional
     * 
     * @return Array $ret, $err 
     *
     */
    public function drawDynamicIps(String $subUsername, String $region=null, int $sessTime=5, int $num=1, String $format="user:pass:host:port")
    {
        list($ret, $err) = $this->post('DrawDynamicIp', array(
            "username" => $subUsername,
            "region" => $region,
            "sessTime" => $sessTime,
            "num" => $num,
            "format" => $format
        ));
        return array($ret, $err);
    }
}
