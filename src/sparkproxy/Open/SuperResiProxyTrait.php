<?php

namespace SparkProxy\Open;

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Http\Error;
use SparkProxy\Http\Client;
use SparkProxy\Http\Proxy;

trait SuperResiProxyTrait
{
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
     * draw dynamic ips
     * 
     * @param String $subUsername sub proxy user, required
     * @param String|null $region region, optional
     * @param int $sessTime session time, optional
     * @param int|null $serverId id of server, optinal
     * @param int $num number of ips, optinal
     * @param String $format format, ['user:pass:host:port', 'user:pass@host:port', 'host:port:user:pass'], optional
     * 
     * @return Array $ret, $err 
     *
     */
    public function drawDynamicProxyAccounts(String $subUsername, ?String $region=null, int $sessTime=5, ?int $serverId=null, int $num=1, String $format="user:pass:host:port")
    {
        list($ret, $err) = $this->post('DrawDynamicProxyAccounts', array(
            "username" => $subUsername,
            "region" => $region,
            "sessTime" => $sessTime,
            "serverId" => $serverId,
            "num" => $num,
            "format" => $format
        ));
        return array($ret, $err);
    }

}
