<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require '../../vendor/autoload.php';
require('functions.php');
require('config.php');

use BackendAI\Kernel;
use BackendAI\Config;

$kernelId = $_POST['kernelId'];
$code = $_POST['code'];
$runId = $_POST['runId'];
$cont = $_POST['cont'];

$args = [
    "accessKey" => $accessKey,
    "secretKey" => $secretKey
];
$config = new Config($args);

$kernel = new Kernel("", $kernelId, $config, $kernelId);
$kernel->destroy();

echo '{}';

