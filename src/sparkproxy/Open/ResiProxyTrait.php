<?php

namespace SparkProxy\Open;

trait ResiProxyTrait
{ 
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

}