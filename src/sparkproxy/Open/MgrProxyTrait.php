<?php

namespace SparkProxy\Open;

trait MgrProxyTrait
{
    /**
     * 添加IP段
     *
     * @param string $countryCode 国家代码
     * @param string $stateCode 省州代码
     * @param string $cityCode 城市代码
     * @param string $address IP地址
     * @param int $port 端口号
     * @param int $netType 网络类型(1原生 2广播)
     * @param string $asn ASN编号 (可选)
     * @param string $isp ISP运营商名称 (可选)
     *
     * @return array 包含返回数据和错误信息
     *          - result (array): 
     *              serverId (int): 服务器ID
     *              countryCode (str): 国家代码
     *              stateCode (str): 省州代码
     *              cityCode (str): 城市代码
     *              address (str): IP地址
     *              port (int): 端口号
     *              netType (int): 网络类型
     *              asn (str): ASN编号
     *              isp (str): ISP运营商
     *          - error (string|null): 错误信息
     */
    public function addServer($countryCode, $stateCode, $cityCode, $address, $port, $netType, $asn = '', $isp = '')
    {
        return $this->post('MgrAddServer', [
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "address" => $address,
            "port" => $port,
            "netType" => $netType,
            "asn" => $asn,
            "isp" => $isp
        ]);
    }

    /**
     * 修改段信息
     *
     * @param int $serverId IP段服务器ID
     * @param int $port 端口号
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function updateServer($serverId, $port)
    {
        return $this->post('MgrUpdateServer', [
            "serverId" => $serverId,
            "port" => $port
        ]);
    }

    /**
     * 批量添加静态IP
     *
     * @param int $serverId 服务器ID
     * @param array $ips IP列表，每个元素为数组: ['ip' => string, 'enabled' => bool]
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function saveServerIps($serverId, $ips)
    {
        return $this->post('MgrSaveServerIps', [
            "serverId" => $serverId,
            "ips" => $ips
        ]);
    }

    /**
     * 分页获取所有的IP段
     *
     * @param string $cidr 模糊搜索IP段
     * @param string $countryCode 国家代码
     * @param string $stateCode 省州代码
     * @param string $cityCode 城市代码
     * @param int|null $accountId 渠道ID (可选)
     * @param int|null $productId 商品ID (可选)
     * @param int $page 页码(从1开始)
     * @param int $pageSize 每页记录数(默认100)
     *
     * @return array Contains return data and error
     *          - result (array): 
     *              data (array)
     *                  total (int): Total count
     *                  page (int): Current page  
     *                  list (array): Item list
     *                      serverId (int): IP segment ID
     *                      cidr (str): CIDR
     *                      netType (int): IP type, 1-native 2-broadcast
     *                      isp (str): ISP provider
     *                      countryCode (str): Country code
     *                      stateCode (str): State code
     *                      cityCode (str): City code
     *                      countryName (str): Country name
     *                      stateName (str): State name
     *                      cityName (str): City name
     *                      total (int): Total IP count
     *                      remains (int): Available IP count
     *                      cooldown (int): IPs in cooldown
     *                      accounts (int): Active accounts
     *                      boundProducts []： 关联的商品
     *                          productId  (int): 商品ID
     *                          name       (str): 商品名称
     *                          region     (str): 区域代码
     *                      assignAccounts []: 可提取的账号
     *                          accountId （int）: 渠道ID
     *                          name       (str): 渠道名称
     *          - error (string|null): Error message if any
     */
    public function listServerInfo(
        $cidr = '',
        $countryCode = '',
        $stateCode = '',
        $cityCode = '',
        $accountId = null,
        $productId = null,
        $page = 1,
        $pageSize = 100
    ) {
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
     * 获取IP段下IP列表
     *
     * @param int|null $serverId IP段ID (可选)
     * @param int $page 页码(从1开始)
     * @param int $pageSize 每页记录数(默认100)
     *
     * @return array Contains return data and error
     *          - result (array):
     *              data (array)
     *                  total (int): Total count
     *                  page (int): Current page
     *                  list (array): Item list
     *                      ip (str): IP address
     *                      countryCode (str): Country code
     *                      countryName (str): Country name
     *                      stateCode (str): State code
     *                      stateName (str): State name
     *                      cityCode (str): City code
     *                      cityName (str): City name
     *                      enabled (bool): Whether enabled
     *                      state (int): Status, 0-free 1-locked 2-used 3-cooldown
     *                      autoUnlockAt (str): Auto unlock time
     *                      accounts (int): Active accounts
     *          - error (string|null): Error message if any
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
     * Get all products bound to specified IP segment
     * 
     * @param int $serverId IP segment ID
     * 
     * @return array Contains return data and error
     *          - result (array): Product list
     *              data (array):
     *                  id (int): Product ID
     *                  name (str): Product name
     *                  sku (str): Product SKU
     *                  type (int): Product type
     *                  price (float): Product price
     *                  currency (str): Currency type
     *          - error (string|null): Error message if any
     */
    public function listServerProducts($serverId)
    {
        return $this->post('MgrListServerProducts', [
            "serverId" => $serverId
        ]);
    }

    /**
     * 绑定商品到IP段
     *
     * @param int $serverId IP段ID
     * @param array $productIds 商品ID列表
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function bindServerToProducts($serverId, $productIds)
    {
        return $this->post('MgrBindServerToProducts', [
            "serverId" => $serverId,
            "productIds" => $productIds
        ]);
    }

    /**
     * 设置可提取IP段的渠道
     *
     * @param int $serverId IP段ID
     * @param array $accountIds 渠道ID列表
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function assignServerToAccounts($serverId, $accountIds)
    {
        return $this->post('MgrAssignServerToAccounts', [
            "serverId" => $serverId,
            "accountIds" => $accountIds
        ]);
    }

    /**
     * 更新IP启用状态
     *
     * @param array $ips IP地址列表
     * @param bool $enabled 是否启用
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function updateIpsEnabled($ips, $enabled)
    {
        return $this->post('MgrUpdateIpsEnabled', [
            "ips" => $ips,
            "enabled" => $enabled
        ]);
    }

    /**
     * 更新IP自动下架状态
     *
     * @param array $ips IP地址列表
     * @param bool $autoOffline 是否自动下架
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function updateIpsAutoOffline($ips, $autoOffline)
    {
        return $this->post('MgrUpdateIpsAutoOffline', [
            "ips" => $ips,
            "autoOffline" => $autoOffline
        ]);
    }

    /**
     * 释放冷却中的IP
     *
     * @param array $ips 需要释放的IP地址列表
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function freeCoolDownIps($ips)
    {
        return $this->post('MgrFreeCoolDownIps', [
            "ips" => $ips
        ]);
    }

    /**
     * 补偿代理IP有效期
     *
     * @param int $accountId 账户ID
     * @param array $ips 需要补偿的IP地址列表
     * @param int $days 需要补偿的天数
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回实例列表，失败返回错误信息
     *         - responseInfo: 请求的Response信息
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
     * 同步IP段绑定的商品库存
     *
     * @param int $serverId IP段服务器ID
     *
     * @return array [result, responseInfo]
     *         - result (array): 成功返回空数组，失败返回错误信息
     *         - responseInfo: 请求的Response信息
     */
    public function syncInventory($serverId)
    {
        return $this->post('MgrSyncInventory', [
            "serverId" => $serverId
        ]);
    }

    /**
     * 查询统计数据
     *
     * @param string $sql SQL查询语句
     *
     * @return array [result, responseInfo]
     *         - result (array): 查询结果
     *         - responseInfo: 请求的Response信息
     */
    public function queryData($sql)
    {
        return $this->post('MgrQueryData', [
            "sql" => $sql
        ]);
    }

    /**
     * 获取所有渠道列表
     *
     * @param int $page 页码，默认1
     * @param int $pageSize 每页数量，默认100
     *
     * @return array [result, responseInfo]
     *         - result (array): 渠道列表信息
     *         - responseInfo: 请求的Response信息
     */
    public function listAccounts($page = 1, $pageSize = 100)
    {
        return $this->post('MgrListAccounts', [
            "page" => $page,
            "pageSize" => $pageSize
        ]);
    }
    
    public function listProducts(int $proxyType, String $countryCode = null, String $areaCode = null, 
        String $cityCode = null, String $productId="", $isp=null, $netType=0, $keywords="", int $page=1, $pageSize=100)
    {
        return $this->post('MgrListProducts', [
            "proxyType" => $proxyType,
            "countryCode" => $countryCode ?? '',
            "areaCode" => $areaCode ?? '',
            "cityCode" => $cityCode ?? '',
            "sku" => $productId ?? '',
            "isp" => $isp ?? '',
            "netType" => $netType ?? 0,
            "keywords" => $keywords ?? '',
            "page" => $page,
            "pageSize" => $pageSize
        ]);
    }
}
