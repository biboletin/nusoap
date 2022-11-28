<?php

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuSoapParser;

include __DIR__ . '/vendor/autoload.php';

$parser = new NuSoapParser();

echo '<pre>' . print_r($parser, true) . '</pre>';
