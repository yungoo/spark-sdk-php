<?php

namespace SparkProxy\Open;

trait MgrStaticServerTrait
{
    /**
     * 添加IP段。
     *
     * @param string $countryCode 国家代码。
     * @param string $stateCode 省州代码。
     * @param string $cityCode 城市代码。
     * @param string $address IP 地址。
     * @param int $port 端口号。
     * @param int $netType 网络类型，1-原生，2-广播。
     * @param string $asn ASN 编号。
     * @param string $isp ISP 运营商名称。
     * @param int $type 段类型，1-独占段，2-共享段。
     *
     * @return array [result, responseInfo]
     */
    public function addServer($countryCode, $stateCode, $cityCode, $address, $port, $netType, $asn = '', $isp = '', $type = 1)
    {
        return $this->post('MgrAddServer', [
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "type" => $type,
            "address" => $address,
            "port" => $port,
            "netType" => $netType,
            "asn" => $asn,
            "isp" => $isp
        ]);
    }

    /**
     * 修改段信息。
     *
     * @param array $serverIds IP 段服务器 ID 列表。
     * @param int $port 端口号。
     * @param int|null $type 段类型，1-独占段，2-共享段；null 表示不更新。
     *
     * @return array [result, responseInfo]
     */
    public function updateServer($serverIds, $port, $type = null)
    {
        $payload = [
            "serverIds" => $serverIds,
            "port" => $port
        ];
        if ($type !== null) {
            $payload["type"] = $type;
        }
        return $this->post('MgrUpdateServer', $payload);
    }

    /**
     * 批量添加静态 IP。
     *
     * @param int $serverId 服务器 ID。
     * @param array $ips IP 列表，每个元素格式为 ['ip' => string, 'enabled' => bool]。
     *
     * @return array [result, responseInfo]
     */
    public function saveServerIps($serverId, $ips)
    {
        return $this->post('MgrSaveServerIps', [
            "serverId" => $serverId,
            "ips" => $ips
        ]);
    }

    /**
     * 分页获取 IP 段列表。
     *
     * @param string $cidr 模糊搜索 CIDR。
     * @param string $countryCode 国家代码。
     * @param string $stateCode 省州代码。
     * @param string $cityCode 城市代码。
     * @param int|null $accountId 渠道 ID。
     * @param int|null $productId 商品 ID。
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     *
     * @return array [result, responseInfo]
     */
    public function listServerInfo($cidr = '', $countryCode = '', $stateCode = '', $cityCode = '', $accountId = null, $productId = null, $page = 1, $pageSize = 100)
    {
        $params = [
            "cidr" => $cidr,
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "accountId" => $accountId,
            "productId" => $productId,
            "page" => $page,
            "pageSize" => $pageSize
        ];
        return $this->post('MgrQueryAreaServerList', $params);
    }

    /**
     * 获取 IP 段下 IP 列表。
     *
     * @param int|null $serverId IP 段 ID。
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     *
     * @return array [result, responseInfo]
     */
    public function listServerIps($serverId = null, $page = 1, $pageSize = 100)
    {
        $params = [
            "serverId" => $serverId,
            "page" => $page,
            "pageSize" => $pageSize
        ];
        return $this->post('MgrListServerIps', $params);
    }

    /**
     * 获取 IP 段绑定的商品列表。
     *
     * @param int $serverId IP 段 ID。
     *
     * @return array [result, responseInfo]
     */
    public function listServerProducts($serverId)
    {
        return $this->post('MgrListServerProducts', [
            "serverId" => $serverId
        ]);
    }

    /**
     * 绑定商品到 IP 段。
     *
     * @param int $serverId IP 段 ID。
     * @param array $productIds 商品 ID 列表。
     *
     * @return array [result, responseInfo]
     */
    public function bindServerToProducts($serverId, $productIds)
    {
        return $this->post('MgrBindServerToProducts', [
            "serverId" => $serverId,
            "productIds" => $productIds
        ]);
    }

    /**
     * 设置可提取 IP 段的渠道。
     *
     * @param int $serverId IP 段 ID。
     * @param array $accountIds 渠道 ID 列表。
     *
     * @return array [result, responseInfo]
     */
    public function assignServerToAccounts($serverId, $accountIds)
    {
        return $this->post('MgrAssignServerToAccounts', [
            "serverId" => $serverId,
            "accountIds" => $accountIds
        ]);
    }

    /**
     * 更新 IP 启用状态。
     *
     * @param array $ips IP 地址列表。
     * @param bool $enabled 是否启用。
     *
     * @return array [result, responseInfo]
     */
    public function updateIpsEnabled($ips, $enabled)
    {
        return $this->post('MgrUpdateIpsEnabled', [
            "ips" => $ips,
            "enabled" => $enabled
        ]);
    }

    /**
     * 更新 IP 自动下架状态。
     *
     * @param array $ips IP 地址列表。
     * @param bool $autoOffline 是否自动下架。
     *
     * @return array [result, responseInfo]
     */
    public function updateIpsAutoOffline($ips, $autoOffline)
    {
        return $this->post('MgrUpdateIpsAutoOffline', [
            "ips" => $ips,
            "autoOffline" => $autoOffline
        ]);
    }

    /**
     * 释放冷却中的 IP。
     *
     * @param array $ips IP 地址列表。
     *
     * @return array [result, responseInfo]
     */
    public function freeCoolDownIps($ips)
    {
        return $this->post('MgrFreeCoolDownIps', [
            "ips" => $ips
        ]);
    }

    /**
     * 补偿代理 IP 有效期。
     *
     * @param int $accountId 账户 ID。
     * @param array $ips IP 地址列表。
     * @param int $days 需要补偿的天数。
     *
     * @return array [result, responseInfo]
     */
    public function compensateProxy($accountId, $ips, $days)
    {
        return $this->post('MgrCompensateProxy', [
            "accountId" => $accountId,
            "ips" => $ips,
            "days" => $days
        ]);
    }

    /**
     * 根据 IP 获取账号下最近开通的实例信息。
     *
     * @param int $accountId 账户 ID。
     * @param array $ips IP 地址列表。
     *
     * @return array [result, responseInfo]
     */
    public function getInstancesByIps($accountId, $ips)
    {
        return $this->post('MgrGetInstancesByIPs', [
            "accountId" => $accountId,
            "ips" => $ips
        ]);
    }

    /**
     * 根据 IP 和用户名获取最近开通的实例信息。
     *
     * @param int $accountId 账户 ID。
     * @param string $ip IP 地址。
     * @param string $username 用户名。
     *
     * @return array [result, responseInfo]
     */
    public function getInstanceByUsername($accountId, $ip, $username)
    {
        return $this->post('MgrGetInstanceByUsername', [
            "accountId" => $accountId,
            "ip" => $ip,
            "username" => $username
        ]);
    }

    /**
     * 将代理实例从一个账号转移到另一个账号。
     *
     * @param string $requestId 请求 ID。
     * @param array $instanceIds 实例 ID 列表。
     * @param int $fromAccountId 原账户 ID。
     * @param int $toAccountId 目标账户 ID。
     *
     * @return array [result, responseInfo]
     */
    public function transferInstances($requestId, $instanceIds, $fromAccountId, $toAccountId)
    {
        return $this->post('MgrTransferInstances', [
            "requestId" => $requestId,
            "fromAccountId" => $fromAccountId,
            "toAccountId" => $toAccountId,
            "instanceIds" => $instanceIds
        ]);
    }

    /**
     * 同步 IP 段绑定的商品库存。
     *
     * @param int $serverId IP 段服务器 ID。
     *
     * @return array [result, responseInfo]
     */
    public function syncInventory($serverId)
    {
        return $this->post('MgrSyncInventory', [
            "serverId" => $serverId
        ]);
    }
}
