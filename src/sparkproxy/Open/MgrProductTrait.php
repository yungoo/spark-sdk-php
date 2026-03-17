<?php

namespace SparkProxy\Open;

trait MgrProductTrait
{
    /**
     * 查询商品列表。
     *
     * @param int $proxyType 代理类型。
     * @param string|null $countryCode 国家代码。
     * @param string|null $areaCode 州/省代码。
     * @param string|null $cityCode 城市代码。
     * @param string $productId 商品 SKU。
     * @param string|null $isp ISP。
     * @param int $netType 网络类型。
     * @param string $keywords 关键字。
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              total (int): 总数
     *              page (int): 当前页
     *              pageSize (int): 每页数量
     *              products (array): 商品列表
     *                  productId (string): 商品 SKU
     *                  productName (string): 商品名称
     *                  proxyType (int): 代理类型
     *                  countryCode (string): 国家代码
     *                  areaCode (string): 州/省代码
     *                  cityCode (string): 城市代码
     *                  costPriceRef (string): 参考成本价
     *                  status (int): 商品状态
     *                  isp (string): ISP
     *                  netType (int): 网络类型
     *                  unit (int): 销售单位
     *                  duration (int): 时长
     *                  bandwidthType (int): 带宽类型
     *                  bandwidth (int): 带宽值
     */
    public function listProducts(int $proxyType, string $countryCode = null, string $areaCode = null, string $cityCode = null, string $productId = "", $isp = null, $netType = 0, $keywords = "", int $page = 1, $pageSize = 100)
    {
        return $this->post('MgrProductList', [
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

    /**
     * 获取商品详情。
     *
     * @param int|null $productId 商品 ID。
     * @param string $sku 商品 SKU。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              id (int): 商品 ID
     *              productId (string): 商品 SKU
     *              productName (string): 商品名称
     *              proxyType (int): 代理类型
     *              countryCode (string): 国家代码
     *              areaCode (string): 州/省代码
     *              cityCode (string): 城市代码
     *              costPriceRef (string): 参考成本价
     *              status (int): 商品状态
     *              isp (string): ISP
     *              netType (int): 网络类型
     *              unit (int): 销售单位
     *              duration (int): 时长
     *              bandwidthType (int): 带宽类型
     *              bandwidth (int): 带宽值
     *              createdAt (string): 创建时间
     *              updatedAt (string): 更新时间
     */
    public function getProduct($productId = null, $sku = '')
    {
        $payload = [];
        if ($productId !== null) {
            $payload["productId"] = $productId;
        }
        if ($sku !== '') {
            $payload["sku"] = $sku;
        }
        return $this->post('MgrProductGet', $payload);
    }

    /**
     * 创建商品。
     *
     * @param int $categoryId 分类 ID。
     * @param string $name 商品名称。
     * @param string $countryCode 国家代码。
     * @param string $stateCode 州/省代码。
     * @param string $cityCode 城市代码。
     * @param int $unit 销售单位。
     * @param int $duration 时长。
     * @param string $costPriceRef 参考成本价。
     * @param int $ispType ISP 类型。
     * @param int $netType 网络类型。
     * @param int $bandwidthType 带宽类型。
     * @param int $bandwidth 带宽值。
     * @param string $isp ISP 名称。
     * @param int $status 商品状态。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段同 getProduct 返回字段
     */
    public function createProduct($categoryId, $name, $countryCode = '', $stateCode = '', $cityCode = '', $unit = 0, $duration = 0, $costPriceRef = '', $ispType = 0, $netType = 0, $bandwidthType = 0, $bandwidth = 0, $isp = '', $status = 0)
    {
        return $this->post('MgrProductCreate', [
            "categoryId" => $categoryId,
            "name" => $name,
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "unit" => $unit,
            "duration" => $duration,
            "costPriceRef" => $costPriceRef,
            "ispType" => $ispType,
            "netType" => $netType,
            "bandwidthType" => $bandwidthType,
            "bandwidth" => $bandwidth,
            "isp" => $isp,
            "status" => $status
        ]);
    }

    /**
     * 更新商品。
     *
     * @param int $productId 商品 ID。
     * @param int $categoryId 分类 ID。
     * @param string $name 商品名称。
     * @param string $countryCode 国家代码。
     * @param string $stateCode 州/省代码。
     * @param string $cityCode 城市代码。
     * @param int $unit 销售单位。
     * @param int $duration 时长。
     * @param string $costPriceRef 参考成本价。
     * @param int $ispType ISP 类型。
     * @param int $netType 网络类型。
     * @param int $bandwidthType 带宽类型。
     * @param int $bandwidth 带宽值。
     * @param string $isp ISP 名称。
     * @param int $status 商品状态。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段同 getProduct 返回字段
     */
    public function updateProduct($productId, $categoryId = 0, $name = '', $countryCode = '', $stateCode = '', $cityCode = '', $unit = 0, $duration = 0, $costPriceRef = '', $ispType = 0, $netType = 0, $bandwidthType = 0, $bandwidth = 0, $isp = '', $status = 0)
    {
        return $this->post('MgrProductUpdate', [
            "productId" => $productId,
            "categoryId" => $categoryId,
            "name" => $name,
            "countryCode" => $countryCode,
            "stateCode" => $stateCode,
            "cityCode" => $cityCode,
            "unit" => $unit,
            "duration" => $duration,
            "costPriceRef" => $costPriceRef,
            "ispType" => $ispType,
            "netType" => $netType,
            "bandwidthType" => $bandwidthType,
            "bandwidth" => $bandwidth,
            "isp" => $isp,
            "status" => $status
        ]);
    }

    /**
     * 切换商品状态。
     *
     * @param int $productId 商品 ID。
     * @param int $status 商品状态。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段同 getProduct 返回字段
     */
    public function toggleProductStatus($productId, $status)
    {
        return $this->post('MgrProductToggleStatus', [
            "productId" => $productId,
            "status" => $status
        ]);
    }

    /**
     * 查询渠道商品售价。
     *
     * @param int|null $accountId 渠道 ID。
     * @param int|null $productId 商品 ID。
     * @param bool|null $status 启用状态。
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              total (int): 总数
     *              page (int): 当前页
     *              pageSize (int): 每页数量
     *              prices (array): 渠道商品售价列表
     *                  accountId (int): 渠道 ID
     *                  accountName (string): 渠道名称
     *                  productId (int): 商品 ID
     *                  productName (string): 商品名称
     *                  sku (string): 商品 SKU
     *                  salesPrice (string): 渠道售价
     *                  enabled (bool): 是否启用
     *                  updatedAt (string): 更新时间
     */
    public function listAccountProductPrices($accountId = null, $productId = null, $status = null, $page = 1, $pageSize = 100)
    {
        $payload = [
            "page" => $page,
            "pageSize" => $pageSize
        ];
        if ($accountId !== null) {
            $payload["accountId"] = $accountId;
        }
        if ($productId !== null) {
            $payload["productId"] = $productId;
        }
        if ($status !== null) {
            $payload["status"] = $status;
        }
        return $this->post('MgrAccountProductPriceList', $payload);
    }

    /**
     * 设置渠道商品售价。
     *
     * @param int $accountId 渠道 ID。
     * @param int $productId 商品 ID。
     * @param string $salesPrice 售价，字符串格式，保留两位小数。
     * @param bool $enabled 是否启用该渠道商品配置。
     * @param string $remark 备注。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              accountName (string): 渠道名称
     *              productId (int): 商品 ID
     *              productName (string): 商品名称
     *              sku (string): 商品 SKU
     *              salesPrice (string): 渠道售价
     *              enabled (bool): 是否启用
     *              updatedAt (string): 更新时间
     */
    public function setAccountProductPrice($accountId, $productId, $salesPrice, $enabled = false, $remark = '')
    {
        $payload = [
            "accountId" => $accountId,
            "productId" => $productId,
            "salesPrice" => $salesPrice,
            "enabled" => $enabled
        ];
        if ($remark !== '') {
            $payload["remark"] = $remark;
        }
        return $this->post('MgrAccountProductPriceSet', $payload);
    }
}
