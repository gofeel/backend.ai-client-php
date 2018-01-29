<?php 
namespace BackendAI;

use GuzzleHttp;

class Kernel
{
    private $config = null;
    private $auth = null;
    private $kernelId = null;

    public function __construct(string $kernelType, string $kernelId=null, Config $config=null, string $token=null)
    {
        $this->kernelType = $kernelType;
        if (!isset($config)) {
            $config = new Config();
        }
        $this->config = $config;

        $this->auth = new Auth($config);

        if($token==null) {
            $token=$this->generateSessionToken();
        }

        if ($kernelId) {
            $this->kernelId = $kernelId;
            //$this->getKernelInfo();
        } else {
            $this->kernelId = $this->createKernel($this->kernelType, $token);
        }
    }

    public function runCode($code, $runId)
    {
        $requestBody = array(
            'mode' => "query",
            'code' => $code,
            'runId' => $runId
        );
        $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/{$this->kernelId}", $requestBody);
        $result = new RunResult($res);
        return $result;
    }

    public function destroy()
    {
        $this->request('DELETE', "/{$this->config->apiVersionMajor}/kernel/{$this->kernelId}", null);
        return;
    }

    public function refresh()
    {
        $this->request('PATCH', "/{$this->config->apiVersionMajor}/kernel/{$this->kernelId}", null);
        return true;
    }

    public function getKernelId()
    {
        return $this->kernelId;
    }

    private function getKernelInfo()
    {
        $res = $this->request('GET', "/{$this->config->apiVersionMajor}/kernel/{$this->kernelId}", null);
        $this->kernelType = $res['lang'];
        return;
    }

    private function createKernel($kernelType, $token)
    {
        $requestBody = array(
            "lang" => $kernelType,
            "clientSessionToken" => $token,
            "resourceLimits" => array(
                "maxMem" => 0,
                "timeout" => 0)
        );

        $res = $this->request('POST', "/{$this->config->apiVersionMajor}/kernel/create", $requestBody);
        $json = json_decode($res, true);

        return $json['kernelId'];
    }

    private function request($method, $queryString, $body="", $contentType="application/json")
    {
        $now = new \DateTime("now", new \DateTimeZone("UTC"));
        $dstring = $now->format(\DateTime::ATOM);

        if ($body === null) {
            $requestBody = '';
        } else {
            $requestBody = json_encode($body);
        }

        $sig = $this->auth->getCredentialString($method, $queryString, $now, $requestBody, $contentType);

        $requestHeaders = array(
            "Content-Type" => "{$contentType}; charset=utf-8",
            "Content-Length" => strlen($requestBody),
            'x-backendai-version' => $this->config->apiVersion,
            "date" => $now->format(\DateTime::ATOM),
            "Authorization" => "BackendAI signMethod=HMAC-SHA256, credential={$sig}"
        );

        $requestInfo = array(
            "headers" => $requestHeaders,
            "cache" => 'default',
            "body" => $requestBody
        );

        $client = new GuzzleHttp\Client();
        try {
            $res = $client->request($method, $this->config->endpoint . $queryString, $requestInfo);
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
