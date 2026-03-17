<?php

namespace SparkProxy\Open;

trait MgrAccountTrait
{
    /**
     * 获取渠道列表。
     *
     * @param int $page 页码，从 1 开始。
     * @param int $pageSize 每页数量。
     * @param string $keywords 关键字，可匹配渠道名称或编码。
     * @param int|null $status 渠道状态，0-禁用，1-启用。
     * @param string $type 渠道类型，例如 YD。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              total (int): 总数
     *              page (int): 当前页
     *              pageSize (int): 每页数量
     *              list (array): 渠道列表
     *                  accountId (int): 渠道 ID
     *                  name (string): 渠道名称
     *                  code (string): 渠道编码
     *                  type (string): 渠道类型
     *                  authority (int): 权限位图
     *                  authorityText (string): 权限说明
     *                  verifySign (bool): 是否开启验签
     *                  status (int): 状态，0-禁用，1-启用
     *                  createdAt (string): 创建时间
     *                  updatedAt (string): 更新时间
     */
    public function listAccounts($page = 1, $pageSize = 100, $keywords = '', $status = null, $type = '')
    {
        $payload = [
            "page" => $page,
            "pageSize" => $pageSize
        ];
        if ($keywords !== '') {
            $payload["keywords"] = $keywords;
        }
        if ($status !== null) {
            $payload["status"] = $status;
        }
        if ($type !== '') {
            $payload["type"] = $type;
        }
        return $this->post('MgrAccountList', $payload);
    }

    /**
     * 创建渠道。
     *
     * @param string $name 渠道名称。
     * @param string $type 渠道类型，例如 YD。
     * @param int $authority 渠道权限位图。
     * @param bool|null $verifySign 是否启用签名校验，传 null 表示不显式设置。
     * @param array|null $params 渠道扩展参数。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              account (array): 渠道信息
     *                  accountId (int): 渠道 ID
     *                  name (string): 渠道名称
     *                  code (string): 渠道编码
     *                  type (string): 渠道类型
     *                  authority (int): 权限位图
     *                  authorityText (string): 权限说明
     *                  verifySign (bool): 是否开启验签
     *                  status (int): 状态，0-禁用，1-启用
     *                  createdAt (string): 创建时间
     *                  updatedAt (string): 更新时间
     *              secret (string): 渠道密钥
     *              fundAccount (array): 资金账户信息
     *                  accountId (int): 渠道 ID
     *                  payMode (string): 结算方式
     *                  availableAmount (string): 可用余额
     *                  frozenAmount (string): 冻结余额
     *                  receivableAmount (string): 应收余额
     *                  creditLimit (string): 授信额度
     *                  status (int): 状态
     *                  updatedAt (string): 更新时间
     */
    public function createAccount($name, $type, $authority = 0, $verifySign = null, $params = null)
    {
        $payload = [
            "name" => $name,
            "type" => $type,
            "authority" => $authority
        ];
        if ($verifySign !== null) {
            $payload["verifySign"] = $verifySign;
        }
        if ($params !== null) {
            $payload["params"] = $params;
        }
        return $this->post('MgrAccountCreate', $payload);
    }

    /**
     * 获取渠道详情。
     *
     * @param int $accountId 渠道 ID。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              account (array): 渠道信息，字段同 createAccount 返回的 account
     *              fundAccount (array): 资金账户信息，字段同 createAccount 返回的 fundAccount
     */
    public function getAccount($accountId)
    {
        return $this->post('MgrAccountGet', [
            "accountId" => $accountId
        ]);
    }

    /**
     * 获取渠道密钥。
     *
     * @param int $accountId 渠道 ID。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              code (string): 渠道编码
     *              secret (string): 32 位渠道密钥
     *              updatedAt (string): 更新时间
     */
    public function getAccountSecret($accountId)
    {
        return $this->post('MgrAccountGetSecret', [
            "accountId" => $accountId
        ]);
    }

    /**
     * 更新渠道。
     *
     * @param int $accountId 渠道 ID。
     * @param string $name 渠道名称。
     * @param int $authority 渠道权限位图。
     * @param bool|null $verifySign 是否启用签名校验，传 null 表示不更新。
     * @param array|null $params 渠道扩展参数，传 null 表示不更新。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              name (string): 渠道名称
     *              code (string): 渠道编码
     *              type (string): 渠道类型
     *              authority (int): 权限位图
     *              authorityText (string): 权限说明
     *              verifySign (bool): 是否开启验签
     *              status (int): 状态，0-禁用，1-启用
     *              createdAt (string): 创建时间
     *              updatedAt (string): 更新时间
     */
    public function updateAccount($accountId, $name = '', $authority = 0, $verifySign = null, $params = null)
    {
        $payload = [
            "accountId" => $accountId,
            "name" => $name,
            "authority" => $authority
        ];
        if ($verifySign !== null) {
            $payload["verifySign"] = $verifySign;
        }
        if ($params !== null) {
            $payload["params"] = $params;
        }
        return $this->post('MgrAccountUpdate', $payload);
    }

    /**
     * 更新渠道密钥。
     *
     * @param int $accountId 渠道 ID。
     * @param string $secret 新密钥，要求 32 位字符串。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              code (string): 渠道编码
     *              secret (string): 更新后的 32 位渠道密钥
     *              updatedAt (string): 更新时间
     */
    public function updateAccountSecret($accountId, $secret)
    {
        return $this->post('MgrAccountUpdateSecret', [
            "accountId" => $accountId,
            "secret" => $secret
        ]);
    }

    /**
     * 启用或停用渠道。
     *
     * @param int $accountId 渠道 ID。
     * @param bool $enabled true-启用，false-停用。
     *
     * @return array [result, responseInfo]
     *         - result['data'] (array): 正常返回字段
     *              accountId (int): 渠道 ID
     *              name (string): 渠道名称
     *              code (string): 渠道编码
     *              type (string): 渠道类型
     *              authority (int): 权限位图
     *              authorityText (string): 权限说明
     *              verifySign (bool): 是否开启验签
     *              status (int): 状态，0-禁用，1-启用
     *              createdAt (string): 创建时间
     *              updatedAt (string): 更新时间
     */
    public function toggleAccountStatus($accountId, $enabled)
    {
        return $this->post('MgrAccountToggleStatus', [
            "accountId" => $accountId,
            "enabled" => $enabled
        ]);
    }
}
