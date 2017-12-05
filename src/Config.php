<?php 
namespace BackendAI;

use GuzzleHttp;

class Config
{
    public $accessKey = null;
    public $secretKey = null;
    public $apiVersionMajor = 'v2';
    public $apiVersion = 'v2.20170315';
    public $hash_type = 'sha256';
    public $endpoint = 'https://api.backend.ai';
    public $userAgent = 'BackendAI Client Library (Php/v0.1)';

    public function __construct(array $options=null)
    {
        if (isset($options['accessKey'])) {
            $this->accessKey = $options['accessKey'];
        } elseif (isset($_SERVER['BACKEND_ACCESS_KEY'])) {
            $this->accessKey = $_SERVER['BACKEND_ACCESS_KEY'];
        } else {
            throw new Exceptions\ConfigException("No credentials.");
            ;
        }

        if (isset($options['secretKey'])) {
            $this->secretKey = $options['secretKey'];
        } elseif (isset($_SERVER['BACKEND_SECRET_KEY'])) {
            $this->secretKey = $_SERVER['BACKEND_SECRET_KEY'];
        } else {
            throw new Exceptions\ConfigException("No credentials.");
            ;
        }

        if (isset($options['endpoint'])) {
            $this->endpoint = $options['endpoint'];
        } elseif (isset($_SERVER['BACKEND_ENDPOINT'])) {
            $this->endpoint = $_SERVER['BACKEND_ENDPOINT'];
        }
    }
}
