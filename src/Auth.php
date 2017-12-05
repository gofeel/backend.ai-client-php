<?php 
namespace BackendAI;

class Auth
{
    private $accessKey = null;
    private $secretKey = null;
    private $hash_type = 'sha256';
    private $hostname = "";
    private $apiVersion = "";

    public function __construct(Config $config)
    {
        $this->accessKey = $config->accessKey;
        $this->secretKey = $config->secretKey;
        $this->hostname = parse_url($config->endpoint, PHP_URL_HOST);
        $this->apiVersion = $config->apiVersion;
    }

    public function getCredentialString($method, $queryString, $date, $bodyValue)
    {
        $signKey = $this->getSignKey($this->secretKey, $date);
        return $this->accessKey . ":" . $this->sign($signKey, 'binary', $this->getAuthenticationString($method, $queryString, $date->format(\DateTime::ATOM), $bodyValue), 'hex');
    }

    public function getAuthenticationString($method, $queryString, $dateValue, $bodyValue)
    {
        $res = hash_init($this->hash_type);
        hash_update($res, $bodyValue);
        $hstring = hash_final($res);
        return "{$method}\n{$queryString}\n" . $dateValue . "\n" . 'host:' . $this->hostname .  "\n".'content-type:application/json' . "\n" . 'x-backendai-version:'.$this->apiVersion . "\n" . $hstring;
    }

    public function getSignKey($secret_key, $now)
    {
        $k1 = $this->sign($secret_key, 'utf8', $this->getDateString($now), 'binary');
        $k2 = $this->sign($k1, 'binary', $this->hostname, 'binary');
        return $k2;
    }

    private function getDateString($d)
    {
        return $d->format('Ymd');
    }

    private function sign($key, $key_encoding, $msg, $digest_type)
    {
        if ($digest_type == 'hex') {
            return bin2hex(hash_hmac($this->hash_type, $msg, $key, true));
        } else {
            return hash_hmac($this->hash_type, $msg, $key, true);
        }
    }
}
