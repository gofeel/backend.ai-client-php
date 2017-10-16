<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require '../../vendor/autoload.php';

require 'functions.php' ;
require 'config.php' ;

use BackendAI\Kernel;
use BackendAI\Config;

$kernelType = isset($_POST['kernelType']) ? $_POST['kernelType'] : "python3";

$args = [
    "accessKey" => $accessKey,
    "secretKey" => $secretKey
];
$config = new Config($args);

$kernel = new Kernel($kernelType, null, $config);
$res = ["kernelId" => $kernel->getKernelId()];

echo json_encode($res);
