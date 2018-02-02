<?php
require '../../vendor/autoload.php';

use BackendAI\Client;
use BackendAI\Config;
use Webmozart\PathUtil\Path;
function setOptions()
{
    $cmd = new Commando\Command();

    $cmd->option('k')
        ->aka('kernel')
        ->default('php')
        ->describedAs('Kernel type');

    $cmd->option('d')
        ->aka('directory')
        ->describedAs('Base Directory')
        ->default(getcwd());
    return $cmd;
}

function getBaseDirectory($path) {
    $path = Path::makeAbsolute($path, getcwd());
    if(!is_dir($path)) {
        return null;
    }
    return $path;
}

function getUnixRelativePath(string $path, string $base) {
    $ap = Path::makeAbsolute(Path::canonicalize($path), getcwd());
    if (strncmp($haystack, $needle, strlen($needle)) === 0) {
        $rp = Path::makeRelative(Path::makeAbsolute(Path::canonicalize($path), getcwd()), $base);
        return $rp;
    }
    return null;
}
