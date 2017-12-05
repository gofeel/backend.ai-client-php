<?php

function sendCode($client, $code, $kernelId, $cont=false) {
    if($cont) {
        return $client->runCode(null, $kernelId);
    } else {
        return $client->runCode($code, $kernelId);
    }
}

