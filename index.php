<?php

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuWsdlCache;

include __DIR__ . '/vendor/autoload.php';

$client = new NuWsdlCache(new Debugger());
echo '<pre>' . print_r($client, true) . '</pre>';
