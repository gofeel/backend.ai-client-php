<?php
require '../../vendor/autoload.php';
require 'functions.php';

use BackendAI\Kernel;
use BackendAI\Config;
use BackendAI\Util;
use Colors\Color;


function main() {
    $c = new Color();
    $c->setTheme(
        array(
            'error' => array('red'),
            'info' => array('green'),
            'internal_error' => array('red'),
            'internal_info' => array('green'),
        )
    );

    $cmd = set_options();

    $path = $cmd['file'];
    if(!file_exists($path))
    {
        echo $c("Input file does not exist.")->error . PHP_EOL;
        return;
    }
    $code = file_get_contents($path);

    try
    {
        $config = new Config();
    }
    catch (Exception $e)
    {
        echo $c($e->getMessage())->error . PHP_EOL;
        return;
    }

    echo $c("Backend AI API Version: " . Util::getAPIVersion($config))->internal_info . PHP_EOL;

    try
    {
        $kernel = new Kernel($cmd['kernel'], null, $config);
    }
    catch (Exception $e)
    {
        echo $c($e->getMessage())->error . PHP_EOL;
        return;
    }

    while(True)
    {
        $r = $kernel->runCode($code);
        echo $r->getStdout();
        echo $r->getStderr();

        if($r->isFinished())
        {
            break;
        }

        if($r->getStatus() == 'waiting-input')
        {
            $code = readline("");
        } else {
            $code = "";
        }
    }
    $kernel->destroy();
}

main();
