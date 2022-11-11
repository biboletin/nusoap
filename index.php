<?php

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuSoapBase;

include __DIR__ . '/vendor/autoload.php';

$base = new NuSoapBase(new Debugger());
$v = ['key'];
$use = 'literal';

class Test {
    public function serialize()
    {
        return true;
    }
}
$class = new Test();

$base->setValue($class);
echo '<pre>' . print_r($base->serializeVal($class, 'value', false, false, false, ['index' => 'bool'], $use, true), true) . '</pre>';
