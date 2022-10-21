<?php

use Biboletin\Nusoap\NuSoapClient;

include __DIR__ . '/vendor/autoload.php';


$client = new NuSoapClient();

// var_dump($client);

var_dump(timestampToIso8601(time()));
