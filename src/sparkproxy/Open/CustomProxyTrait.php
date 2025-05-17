<?php

namespace SparkProxy\Open;

use SparkProxy\Auth;
use SparkProxy\Config;
use SparkProxy\Http\Error;
use SparkProxy\Http\Client;
use SparkProxy\Http\Proxy;

trait CustomProxyTrait
{

    /**
     * direct create proxy instance
     *
     * @param String $reqOrderNo order number
     * @param array $ips ip array
     * @param bool $shareable shareable or not
     * @param int $duration duration of proxy, unit: day
     * @param int $unit unit of duration, 1: day, 2: week, 3: month, according to the product destail
     * @param ?array $customer optional customer/order info, array with optional keys: agent (string), customer (string), coupon (string), amount (string)
     */
    public function customCreateProxy(String $reqOrderNo, array $ips, bool $shareable, int $duration, int $unit, ?array $customer = null)
    {
        $payload = array(
            "reqOrderNo" => $reqOrderNo,
            "ips" => $ips,
            "shareable" => $shareable,
            "duration" => $duration,
            "unit" => $unit
        );

        if ($customer !== null) {
            $payload["customer"] = $customer;
        }

        return $this->post('CustomCreateProxy', $payload);
    }

    /**
     * custom renew proxy, will extends the exipration of instance
     * 
     * @param String $reqOrderNo new order number
     * @param Array $instances [{account: '', duration: 30, unit: 1}] all instance must in same order & duration * unit should be equals.
     *                 account: ip:port:user:password
     *                 unit: 1: day, 2: week, 3: month, 4: year, according to the product destail
     * @return Array
     */
    public function customRenewProxy(String $reqOrderNo, Array $instances)
    {
        list($ret, $info) = $this->post('CustomRenewProxy', array(
            "reqOrderNo" => $reqOrderNo,
            "instances" => $instances
        ));
        return array($ret, $info);
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
            "accounts" => $accounts
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

}
