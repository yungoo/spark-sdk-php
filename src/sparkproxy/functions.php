<?php

namespace SparkProxy;

use SparkProxy\Config;
use \phpseclib3\Crypt\RSA;
use \phpseclib3\Crypt\PublicKeyLoader;
use \phpseclib3\File\X509;

if (!defined('SPARKPROXY_FUNCTIONS_VERSION')) {
    define('SPARKPROXY_FUNCTIONS_VERSION', Config::SDK_VER);

    /**
     * 计算文件的crc32检验码:
     *
     * @param $file string  待计算校验码的文件路径
     *
     * @return string 文件内容的crc32校验码
     */
    function crc32_file($file)
    {
        $hash = hash_file('crc32b', $file);
        $array = unpack('N', pack('H*', $hash));
        return sprintf('%u', $array[1]);
    }

    /**
     * 计算输入流的crc32检验码
     *
     * @param $data string 待计算校验码的字符串
     *
     * @return string 输入字符串的crc32校验码
     */
    function crc32_data($data)
    {
        $hash = hash('crc32b', $data);
        $array = unpack('N', pack('H*', $hash));
        return sprintf('%u', $array[1]);
    }

    /**
     * 对提供的数据进行urlsafe的base64编码。
     *
     * @param string $data 待编码的数据，一般为字符串
     *
     * @return string 编码后的字符串
     * @link http://developer.qiniu.com/docs/v6/api/overview/appendix.html#urlsafe-base64
     */
    function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }

    /**
     * 对提供的urlsafe的base64编码的数据进行解码
     *
     * @param string $str 待解码的数据，一般为字符串
     *
     * @return string 解码后的字符串
     */
    function base64_urlSafeDecode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $key 要排序的键
     * @param string $sort  排序类型 SORT_ASC SORT_DESC
     * return array 排序后的数组
     */
    function arraySort($array, $key, $sort = SORT_ASC)
    {
        $keysValue = array();
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$key];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * Wrapper for JSON decode that implements error detection with helpful
     * error messages.
     *
     * @param string $json JSON data to parse
     * @param bool $assoc When true, returned objects will be converted
     *                        into associative arrays.
     * @param int $depth User specified recursion depth.
     *
     * @return mixed
     * @throws \InvalidArgumentException if the JSON cannot be parsed.
     * @link http://www.php.net/manual/en/function.json-decode.php
     */
    function json_decode($json, $assoc = false, $depth = 512)
    {
        static $jsonErrors = array(
            JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
            JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
        );

        if (empty($json)) {
            return null;
        }
        $data = \json_decode($json, $assoc, $depth);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $last = json_last_error();
            throw new \InvalidArgumentException(
                'Unable to parse JSON data: '
                . (isset($jsonErrors[$last])
                    ? $jsonErrors[$last]
                    : 'Unknown error')
            );
        }

        return $data;
    }

    /**
     * array 辅助方法，无值时不set
     *
     * @param array $array 待操作array
     * @param string $key key
     * @param string $value value 为null时 不设置
     *
     * @return array 原来的array，便于连续操作
     */
    function setWithoutEmpty(&$array, $key, $value)
    {
        if (!empty($value)) {
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * 将 parse_url 的结果转换回字符串
     * TODO: add unit test
     *
     * @param $parsed_url - parse_url 的结果
     * @return string
     */
    function unparse_url($parsed_url)
    {

        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';

        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';

        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';

        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';

        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';

        $pass     = ($user || $pass) ? "$pass@" : '';

        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';

        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';

        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    // polyfill ucwords for `php version < 5.4.32` or `5.5.0 <= php version < 5.5.16`
    if (version_compare(phpversion(), "5.4.32") < 0 ||
        (
            version_compare(phpversion(), "5.5.0") >= 0 &&
            version_compare(phpversion(), "5.5.16") < 0
        )
    ) {
        function ucwords($str, $delimiters = " \t\r\n\f\v")
        {
            $delims = preg_split('//u', $delimiters, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($delims as $delim) {
                $str = implode($delim, array_map('ucfirst', explode($delim, $str)));
            }

            return $str;
        }
    } else {
        function ucwords($str, $delimiters)
        {
            return \ucwords($str, $delimiters);
        }
    }
    
    function toBytes($data, $encoding = 'utf-8') {
        if (PHP_MAJOR_VERSION < 7) {
            return $data;
        } else {
            return is_string($data) ? $data : utf8_encode($data);
        }
    }
    
    function toString($data, $encoding = 'utf-8') {
        if (is_string($data)) {
            return $data;
        } elseif (is_array($data)) {
            return implode('', $data);
        } else {
            return (string) $data;
        }
    }
    
    function fromHex($s) {
        return hex2bin($s);
    }
    
    function toHex($data) {
        return bin2hex($data);
    }
    
    function rsaLoadPemPrivateKey($priKey) {
        return PublicKeyLoader::load($priKey);
    }
    
    function rsaLoadPemPublicKey($pubKey) {
        return PublicKeyLoader::load($pubKey);
    }
    
    function rsaPublicEncrypt($msg, $publicKey) {
        $decodedMsg = toBytes($msg);
    
        $encryptedMsg = $publicKey->encrypt($decodedMsg);
    
        return toHex($encryptedMsg);
    }
    
    function rsaPrivateDecrypt($encryptedMsgHex, $privateKey) {
        $encryptedMsg = fromHex($encryptedMsgHex);
    
        $decryptedMsg = $privateKey->decrypt($encryptedMsg);

        return $decryptedMsg;
    }
    
    function rsaSign($message, $privateKey) {
        $signature = $privateKey->sign(toBytes($message));
        return toHex($signature);
    }
    
    function rsaVerify($sign, $message, $publicKey) {
        $decodedSign = fromHex($sign);
        return $publicKey->verify(toBytes($message), $decodedSign);
    }
}
