<?php 
namespace BackendAI;

use GuzzleHttp;

class Kernel
{
    private $config = null;
    private $auth = null;
    private $sessionToken = null;

    public function __construct(string $kernelType, string $sessionToken=null, Config $config=null)
    {
        $this->kernelType = $kernelType;
        if (!isset($config)) {
            $config = new Config();
        }
        $this->config = $config;

        $this->auth = new Auth($config);

        if($sessionToken == null) {
            $sessionToken = $this->generateSessionToken();
        }

        $this->sessionToken = $this->createKernelIfNotExists($sessionToken);
    }

    public function execute($mode, $runId, $code="", $options=null)
    {
        $requestBody = array(
            'mode' => $mode,
            'code' => $code,
            'runId' => $runId
        );
        if(!is_null($options)) {
            $requestBody['options'] = $options;
        }
        $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}", $requestBody);
        $result = new RunResult($res);
        return $result;
    }

    public function destroy()
    {
        $this->request('DELETE', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}", null);
        return;
    }

    public function refresh()
    {
        $this->request('PATCH', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}", null);
        return true;
    }

    public function getKernelId()
    {
        return $this->kernelId;
    }

    private function getKernelInfo()
    {
        $res = $this->request('GET', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}", null);
        $this->kernelType = $res['lang'];
        return;
    }

    private function createKernelIfNotExists($token)
    {
        $requestBody = array(
            "lang" => $this->kernelType,
            "clientSessionToken" => $token,
            "resourceLimits" => array(
                "maxMem" => 0,
                "timeout" => 0)
        );

        $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/create", $requestBody);
        $json = json_decode($res, true);

        return $json['kernelId'];
    }

    public function upload($files)
    {
        $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}/upload", "", $files);
        try {
            $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/{$this->sessionToken}/upload", "", $files);
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $responseBody = $e->getResponse()->getBody(true);
            echo $responseBody;
        }
    }

    private function request($method, $queryString, $body="", $files=[])
    {
        $url = $this->config->endpoint . $queryString;
        $now = new \DateTime("now", new \DateTimeZone("UTC"));
        $dstring = $now->format(\DateTime::ATOM);

        $requestInfo = array(
            "cache" => 'default',
        );
        $requestHeaders = array(
            'x-backendai-version' => $this->config->apiVersion,
            "date" => $now->format(\DateTime::ATOM),
        );
        if(is_array($files) && count($files) > 0) {
            $authBaseString = '';
            $contentType = "multipart/form-data";
            $multipart = [];
            foreach($files as $k => $v) {
                $multipart[] = [
                    'name' => 'src',
                    'filename' => $k,
                    'contents' => fopen($v, 'r')
                ];
            }
            $requestInfo['multipart'] = $multipart;
        } else if(is_array($body)){
            $authBaseString = '';
            $authBaseString = json_encode($body);
            $contentType = "application/json";
            $requestInfo['json'] = $body;
        } else {
            if ($body === null) {
                $authBaseString = '';
            } else {
                $authBaseString = $body;
            }
            $contentType = "application/json";
            $requestInfo['json'] = json_decode($authBaseString);
            $requestHeaders["Content-Type"] = $contentType;
        }

        $sig = $this->auth->getCredentialString($method, $queryString, $now, $authBaseString, $contentType);
        $requestHeaders["Authorization"] = "BackendAI signMethod=HMAC-SHA256, credential={$sig}";

        $requestInfo['headers'] = $requestHeaders;

        $client = new GuzzleHttp\Client();
        try {
            $res = $client->request($method, $url, $requestInfo);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            throw $e;
        }
        return $res->getBody();
    }

    private function generateSessionToken() {
        return uniqid();
    }

    public function generateRunId() {
        return uniqid();
    }
}
