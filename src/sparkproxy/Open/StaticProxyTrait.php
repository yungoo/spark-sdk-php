<?php
namespace SparkProxy\Open;


trait StaticProxyTrait
{
    
    public function getProductStock(int $proxyType, String $countryCode = null, String $areaCode = null, 
        String $cityCode = null, String $productId="", $isp=null, $netType=0, int $page=1, $pageSize=100)
    {
        return $this->post('GetProductStock', array(
            "proxyType" => $proxyType,
            "countryCode" => $countryCode ?? '',
            "areaCode" => $areaCode ?? '',
            "cityCode" => $cityCode ?? '',
            "productId" => $productId ?? '',
            "isp" => $isp ?? '',
            "netType" => $netType ?? 0,
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
     *              int count quantity of proxy
     */
    public function createProxy(String $reqOrderNo, String $productId, int $quantity, int $duration, int $unit, array $rules)
    {
        return $this->post('CreateProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "productId" => $productId,
            "amount" => $quantity,
            "duration" => $duration,
            "unit" => $unit,
            "cidrBlocks" => $rules ?? []
        ));
    }

    /**
     * renew proxy, will extends the exipration of instance
     * 
     * @param String $reqOrderNo new order number
     * @param Array $instances [{instanceId: '', duration: 30, unit: 1}] all instance must in same order & duration * unit should be equals.
     *                 unit: 1: day, 2: week, 3: month, 4: year, according to the product destail
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

}
