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
     * @param string $asn ASN编号
     * @param string $isp ISP运营商名称
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
    public function addCidr($countryCode, $stateCode, $cityCode, $address, $port, $netType, $asn = '', $isp = '')
    {
        list($ret, $err) = $this->post('MgrAddCidr', array(
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "address" => $address,
            "port" => $port,
            "netType" => $netType,
            "asn" => $asn,
            "isp" => $isp
        ));
        return array($ret, $err);
    }

    /**
     * 批量添加静态IP
     *
     * @param int $serverId 服务器ID
     * @param array $ips IP列表，每个元素为关联数组包含:
     *              - ip (string): IP地址
     *              - enabled (bool): 是否启用
     *
     * @return array 包含返回数据和错误信息
     *          - result (array): 成功返回空数组，失败返回错误信息
     *          - error (string|null): 错误信息
     */
    public function saveCidrIps($serverId, $ips)
    {
        list($ret, $err) = $this->post('MgrSaveCidrIps', array(
            "serverId" => $serverId,
            "ips" => $ips
        ));
        return array($ret, $err);
    }

    
    /**
     * List paginated IP segment (CIDR) list
     *
     * @param string $cidr Fuzzy search for CIDR
     * @param string $countryCode Country code
     * @param string $stateCode State code 
     * @param string $cityCode City code
     * @param int $page Page number, start from 1
     * @param int $pageSize Page size, default 100
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
     *          - error (string|null): Error message if any
     */
    public function listCidrInfo($cidr, $countryCode, $stateCode, $cityCode, $page, $pageSize) 
    {
        list($ret, $err) = $this->post('MgrQueryAreaCidrList', array(
            "cidr" => $cidr,
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "page" => $page,
            "pageSize" => $pageSize
        ));
        return array($ret, $err);
    }

    /**
     * List paginated IP detail list
     *
     * @param string $cidr Fuzzy search for CIDR
     * @param int $page Page number, start from 1
     * @param int $pageSize Page size, default 100
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
    public function listCidrIps($cidr, $page, $pageSize) 
    {
        list($ret, $err) = $this->post('MgrListCidrIps', array(
            "cidr" => $cidr,
            "page" => $page,
            "pageSize" => $pageSize
        ));
        return array($ret, $err);
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
    public function listCidrBoundProducts(int $serverId)
    {
        list($ret, $err) = $this->post('MgrListCidrBoundProducts', array(
            "serverId" => $serverId
        ));
        return array($ret, $err);
    }

    /**
     * Bind multiple products to specified IP segment
     * 
     * @param int $serverId IP segment ID
     * @param array $productIds Array of product sku list
     * 
     * @return array Contains return data and error
     *          - result (array): Empty on success
     *          - error (string|null): Error message if any
     */
    public function bindCidrToProducts(int $serverId, array $productIds)
    {
        list($ret, $err) = $this->post('MgrBindCidrToProducts', array(
            "serverId" => $serverId,
            "productIds" => $productIds
        ));
        return array($ret, $err);
    }

    /**
     * Sync inventory of products bound to IP segment
     * 
     * @param int $serverId IP segment server ID
     * 
     * @return array Contains return data and error
     *          - result (array): Empty on success
     *          - error (string|null): Error message if any
     */
    public function syncCidrBoundProductsInventory(int $serverId)
    {
        list($ret, $err) = $this->post('MgrSyncCidrBoundProductsInventory', array(
            "serverId" => $serverId
        ));
        return array($ret, $err);
    }

    /**
     * Update IPs status
     *
     * @param array $ips Array of IPs
     * @param bool $enabled Whether to enable
     * 
     * @return array Contains return data and error
     *          - result (array): Empty on success
     *          - error (string|null): Error message if any
     */
    public function updateIpsEnabled(array $ips, bool $enabled) {
        list($ret, $err) = $this->post('MgrUpdateIpsEnabled', array(
            "ips" => $ips,
            "enabled" => $enabled
        ));
        return array($ret, $err);
    }

    /**
     * Update IPs auto offline status
     *
     * @param array $ips Array of IPs
     * @param bool $autoOffline Whether to auto offline
     * 
     * @return array Contains return data and error
     *          - result (array): Empty on success
     *          - error (string|null): Error message if any
     */
    public function updateIpsAutoOffline(array $ips, bool $autoOffline)
    {
        list($ret, $err) = $this->post('MgrUpdateIpsAutoOffline', array(
            "ips" => $ips,
            "autoOffline" => $autoOffline
        ));
        return array($ret, $err);
    }

    /**
     * Free cool down IPs
     *
     * @param array $ips Array of IPs to free
     * 
     * @return array Contains return data and error
     *          - result (array): Empty on success
     *          - error (string|null): Error message if any
     */
    public function freeCoolDownIps(array $ips)
    {
        list($ret, $err) = $this->post('MgrFreeCoolDownIps', array(
            "ips" => $ips
        ));
        return array($ret, $err);
    }

    /**
     * Query stats data
     * 
     * @param string $sql SQL query
     * 
     * @return array Contains return data and error
     *          - result (array): Query results
     *          - error (string|null): Error message if any
     */
    public function queryData(string $sql)
    {
        list($ret, $err) = $this->post('MgrQueryData', array(
            "sql" => $sql
        ));
        return array($ret, $err);
    }

}
