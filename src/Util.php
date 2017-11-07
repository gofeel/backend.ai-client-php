<?php 
namespace BackendAI;

use GuzzleHttp;

abstract class Util
{
    public static function getAPIVersion(Config $config)
    {
        $now = new \DateTime("now", new \DateTimeZone("UTC"));

        $requestHeaders = array(
            "Content-Type" => "application/json",
            "X-Sorna-Date" => $now->format(\DateTime::ATOM)
        );

        $requestInfo = array(
            'method' => 'GET',
            'headers' => $requestHeaders,
            'mode' => 'cors',
            'cache' => 'default'
        );
        $url = $config->endpoint . '/' . $config->apiVersionMajor;
        $client = new GuzzleHttp\Client();
        try {
            $res = $client->request($requestInfo['method'], $url, $requestInfo);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            throw $e;
        }
        $json = json_decode($res->getBody(), true);
        return $json['version'];
    }
}
