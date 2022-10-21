<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\Traits\NuSoapGetters;
use Biboletin\Nusoap\Traits\NuSoapSetters;
use Biboletin\Nusoap\Traits\NuSoapProperties;

class NuSoap
{
    use NuSoapProperties;
    use NuSoapSetters;
    use NuSoapGetters;

    private Debugger $debugger;

    public function __construct(Debugger $debugger)
    {
        $this->debugger = $debugger;
    }
}
