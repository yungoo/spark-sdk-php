<?php

namespace SparkProxy;

final class Config
{
    const SDK_VER = '0.1.0';

    const API_HOST = 'https://oapi.sparkproxy.com';
    // const SANDBOX_API_HOST = 'http://8.130.48.76:16801';
    const SANDBOX_API_HOST = 'http://localhost:8080';
    const API_VERSION = '2024-04-16';

    const PUBLIC_KEY='-----BEGIN PUBLIC KEY-----
    MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDEovKByCtmQlJJBsZzSyc97gI1
    Dp62XP8SrvUPBqGlWGEKNh60n2njcUUIkMDitM2yb1vuRluu3Mzk/TvaE23JOMqA
    0HPsd7IG9rNCyn7vcRXvVj1jLTVw/J+f7FJB4OzZqmOEe8kq69WCP4JIkXPvAT53
    wvarJGl6cincWuZvIwIDAQAB
    -----END PUBLIC KEY-----';
}
