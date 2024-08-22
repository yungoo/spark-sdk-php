<?php
namespace SparkProxy;

class Auth
{
    private $supplierNo;
    private $secretKey;

    public function __construct($supplierNo, $secretKey)
    {
        $err = $this->checkKey($supplierNo, $secretKey);
        if ($err !== null) {
            throw new \Exception($err);
        }
        
        $this->supplierNo = $supplierNo;
        $this->secretKey = $secretKey;
    }

    public function getSupplierNo()
    {
        return $this->supplierNo;
    }

    private function checkKey($supplierNo, $secretKey)
    {
        if (empty($supplierNo) || empty($secretKey)) {
             return 'Invalid key';
        }
        return null;
    }

    public function encryptParams($msg)
    {
        return encrypt_data($msg, $this->secretKey);
    }

    public function decryptParams($encryptMsg)
    {
        return decrypt_data($encryptMsg, $this->secretKey);
    }

}
