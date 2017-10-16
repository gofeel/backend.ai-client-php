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
        $this->assertEquals($s, "TESTESTSERSERESTSET:6b9fbf834e437a78c2c53ad424d3e21cf97d1f2362d2764d26d46386f6617a0e");
    }
}
