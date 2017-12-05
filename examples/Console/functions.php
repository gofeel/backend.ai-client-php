<?php
require '../../vendor/autoload.php';

use BackendAI\Client;
use BackendAI\Config;

function set_options()
{
    $cmd = new Commando\Command();

    $cmd->option('k')
        ->aka('kernel')
        ->default('php')
        ->describedAs('Kernel type');

    $cmd->option('f')
        ->aka('file')
        ->describedAs('File');
    return $cmd;
}
