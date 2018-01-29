<?php

namespace BackendAI\Tests;

use BackendAI;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $initialConfig = [ ];

    public function setUp()
    {
    }

    public function testWrongArguments()
    {
        $args = [
        ];

        unset($_SERVER['BACKEND_ACCESS_KEY']);
        unset($_SERVER['BACKEND_SECRET_KEY']);

        $this->setExpectedException('BackendAI\Exceptions\ConfigException');
        $config = new BackendAI\Config($args);
    }
}
