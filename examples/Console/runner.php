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

    $cmd = setOptions();
    $base = getBaseDirectory($cmd['d']);
    $files = [];
    $args = $cmd->getArgumentValues();
    foreach($args as $arg) {
        $p = getUnixRelativePath($arg, $base);
        $files[$p] = $arg;
    }

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
    $kernel->upload($files);

    $runId = $kernel->generateRunId();
    while(True)
    {
        $r = $kernel->runCode($runId);
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
