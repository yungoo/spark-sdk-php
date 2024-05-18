<?php
namespace SparkProxy;

use Ramsey\Uuid\Uuid;

class Auth
{
    private $supplierNo;
    private $privateKey;
    private $publicKey;

    public function __construct($supplierNo, $privateKey, $publicKey = null)
    {
        $err = $this->checkKey($supplierNo, $privateKey);
        if ($err !== null) {
            throw new \Exception($err);
        }
        
        $this->supplierNo = $supplierNo;
        $this->privateKey = rsaLoadPemPrivateKey($privateKey);

        $publicKey = $publicKey ?? Config::PUBLIC_KEY;
        if ($publicKey !== null) {
            $this->publicKey = rsaLoadPemPublicKey($publicKey);
        }
    }

    public function getSupplierNo()
    {
        return $this->supplierNo;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function tokenOfRequest($req)
    {
        $message = 'supplierNo=' . $req['supplierNo'] . '&timestamp=' . $req['timestamp'];
        return rsaSign($message, $this->privateKey);
    }

    private function checkKey($supplierNo, $privateKey)
    {
        if (empty($supplierNo) || empty($privateKey)) {
             return 'Invalid key';
        }
        return null;
    }

    public function encryptUsingRemotePublicKey($msg)
    {
        return rsaPublicEncrypt($msg, $this->publicKey);
    }

    public function decryptUsingPrivateKey($encryptMsg)
    {
        return rsaPrivateDecrypt($encryptMsg, $this->privateKey);
    }

    public function verifyCallback($supplierNo, $sign, $reqId, $timestamp)
    {
        if (empty($supplierNo)) {
            return "签名参数supplierNo未提供。reqId: $reqId";
        }
        if (empty($sign)) {
            return "签名参数sign未提供。reqId: $reqId";
        }
        if (time() - $timestamp > 600) {
            return "签名已过期。reqId: $reqId";
        }

        $strToSign = "supplierNo=$supplierNo&timestamp=$timestamp";

        $ret = rsaVerify($sign, $strToSign, $this->publicKey);
        if (!$ret) {
            return "签名验证失败。reqId: $reqId";
        }
        return null;
    }

}
