<?php

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuSoapServerMime;

include __DIR__ . '/vendor/autoload.php';


$client = new NuSoapServerMime(new Debugger());

echo '<pre>' . print_r($client, true) . '</pre>';

// var_dump(timestampToIso8601(time()));
// var_dump(iso8601ToTimestamp(timestampToIso8601(time())));


