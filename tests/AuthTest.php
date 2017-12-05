<?php

namespace BackendAI\Tests;

use BackendAI;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    private $initialConfig = [ ];

    public function setUp()
    {
    }

    public function testAuthString()
    {
        $args = [
            'accessKey' => 'TESTESTSERSERESTSET',
            'secretKey' => 'KJSAKDFJASKFDJASDFJSAFDJSJFSAJFSDF'
        ];
        $config = new BackendAI\Config($args);
        $auth = new BackendAI\Auth($config);
        $requestBody = null;
        $method = "POST";
        $queryString = "v2/kernel/create";
        $now = new \DateTime("2017-10-30", new \DateTimeZone("UTC"));
        $s = $auth->getCredentialString($method, $queryString, $now, $requestBody);
        $this->assertEquals($s, "TESTESTSERSERESTSET:538b0c9b0b47ba77db987af39f98b2590c613dfc47133a135381f321798f97cd");
    }
}
