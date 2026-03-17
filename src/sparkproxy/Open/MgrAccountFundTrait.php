<?php

namespace SparkProxy\Open;

trait MgrAccountFundTrait
{
    /**
     * 获取渠道资金账户。
     *
     * @param int $accountId 渠道 ID。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              payMode (string): 结算方式，POSTPAID / PREPAID
     *              availableAmount (string): 可用余额
     *              frozenAmount (string): 冻结余额
     *              receivableAmount (string): 应收余额
     *              creditLimit (string): 授信额度
     *              status (int): 状态
     *              updatedAt (string): 更新时间
     */
    public function getAccountFund($accountId)
    {
        return $this->post('MgrAccountFundGet', [
            "accountId" => $accountId
        ]);
    }

    /**
     * 更新渠道结算方式。
     *
     * @param int $accountId 渠道 ID。
     * @param string $payMode 结算方式，支持 POSTPAID / PREPAID。
     * @param string $creditLimit 授信额度，切换到后付费时可一并传入。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              payMode (string): 结算方式，POSTPAID / PREPAID
     *              availableAmount (string): 可用余额
     *              frozenAmount (string): 冻结余额
     *              receivableAmount (string): 应收余额
     *              creditLimit (string): 授信额度
     *              status (int): 状态
     *              updatedAt (string): 更新时间
     */
    public function updateAccountFundPayMode($accountId, $payMode, $creditLimit = '')
    {
        $payload = [
            "accountId" => $accountId,
            "payMode" => $payMode
        ];
        if ($creditLimit !== '') {
            $payload["creditLimit"] = $creditLimit;
        }
        return $this->post('MgrAccountFundUpdatePayMode', $payload);
    }

    /**
     * 分页查询渠道资金流水。
     *
     * @param int $accountId 渠道 ID。
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     * @param string $bizType 业务类型过滤。
     * @param string $orderNo 订单号过滤。
     * @param string $bizNo 业务单号过滤。
     * @param string $source 来源过滤。
     * @param string $startTime 开始时间，格式与服务端约定保持一致。
     * @param string $endTime 结束时间，格式与服务端约定保持一致。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              total (int): 总数
     *              page (int): 当前页
     *              pageSize (int): 每页数量
     *              list (array): 流水列表
     *                  ledgerId (int): 流水 ID
     *                  payMode (string): 结算方式
     *                  bizType (string): 业务类型
     *                  amount (string): 金额
     *                  availableAfter (string): 变更后可用余额
     *                  frozenAfter (string): 变更后冻结余额
     *                  receivableAfter (string): 变更后应收余额
     *                  orderNo (string): 订单号
     *                  orderProductId (int): 订单商品 ID
     *                  instanceId (int): 实例 ID
     *                  bizNo (string): 业务单号
     *                  idemKey (string): 幂等键
     *                  source (string): 来源
     *                  detailJson (string): 扩展详情 JSON
     *                  createdAt (string): 创建时间
     */
    public function listAccountFundLedgers($accountId, $page = 1, $pageSize = 100, $bizType = '', $orderNo = '', $bizNo = '', $source = '', $startTime = '', $endTime = '')
    {
        $payload = [
            "accountId" => $accountId,
            "page" => $page,
            "pageSize" => $pageSize
        ];
        if ($bizType !== '') {
            $payload["bizType"] = $bizType;
        }
        if ($orderNo !== '') {
            $payload["orderNo"] = $orderNo;
        }
        if ($bizNo !== '') {
            $payload["bizNo"] = $bizNo;
        }
        if ($source !== '') {
            $payload["source"] = $source;
        }
        if ($startTime !== '') {
            $payload["startTime"] = $startTime;
        }
        if ($endTime !== '') {
            $payload["endTime"] = $endTime;
        }
        return $this->post('MgrAccountFundListLedgers', $payload);
    }

    /**
     * 为渠道资金账户充值。
     *
     * @param int $accountId 渠道 ID。
     * @param string $amount 充值金额，字符串格式，保留两位小数。
     * @param string $bizNo 业务单号。
     * @param string $idemKey 幂等键。
     * @param string $remark 备注。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              ledgerId (int): 流水 ID
     *              payMode (string): 结算方式
     *              bizType (string): 业务类型，固定为 recharge
     *              amount (string): 金额
     *              availableAfter (string): 变更后可用余额
     *              frozenAfter (string): 变更后冻结余额
     *              receivableAfter (string): 变更后应收余额
     *              orderNo (string): 订单号
     *              orderProductId (int): 订单商品 ID
     *              instanceId (int): 实例 ID
     *              bizNo (string): 业务单号
     *              idemKey (string): 幂等键
     *              source (string): 来源
     *              detailJson (string): 扩展详情 JSON
     *              createdAt (string): 创建时间
     */
    public function rechargeAccountFund($accountId, $amount, $bizNo = '', $idemKey = '', $remark = '')
    {
        $payload = [
            "accountId" => $accountId,
            "amount" => $amount
        ];
        if ($bizNo !== '') {
            $payload["bizNo"] = $bizNo;
        }
        if ($idemKey !== '') {
            $payload["idemKey"] = $idemKey;
        }
        if ($remark !== '') {
            $payload["remark"] = $remark;
        }
        return $this->post('MgrAccountFundRecharge', $payload);
    }

    /**
     * 调整渠道授信额度。
     *
     * @param int $accountId 渠道 ID。
     * @param string $creditLimit 授信额度，字符串格式，保留两位小数。
     * @param string $bizNo 业务单号。
     * @param string $idemKey 幂等键。
     * @param string $remark 备注。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              payMode (string): 结算方式，POSTPAID / PREPAID
     *              availableAmount (string): 可用余额
     *              frozenAmount (string): 冻结余额
     *              receivableAmount (string): 应收余额
     *              creditLimit (string): 授信额度
     *              status (int): 状态
     *              updatedAt (string): 更新时间
     */
    public function adjustAccountCreditLimit($accountId, $creditLimit, $bizNo = '', $idemKey = '', $remark = '')
    {
        $payload = [
            "accountId" => $accountId,
            "creditLimit" => $creditLimit
        ];
        if ($bizNo !== '') {
            $payload["bizNo"] = $bizNo;
        }
        if ($idemKey !== '') {
            $payload["idemKey"] = $idemKey;
        }
        if ($remark !== '') {
            $payload["remark"] = $remark;
        }
        return $this->post('MgrAccountFundAdjustCreditLimit', $payload);
    }
}
