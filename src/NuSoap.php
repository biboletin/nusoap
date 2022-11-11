<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\NuSoapBase;
use Biboletin\Nusoap\Debugger;

class NuSoap extends NuSoapBase
{
    public function __construct()
    {
        $this->debugger = new Debugger();
    }
}
