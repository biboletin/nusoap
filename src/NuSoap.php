<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\Traits\NuSoapProperties;
use Biboletin\Nusoap\Debugger;

class NuSoap
{
    use NuSoapProperties;

    private Debugger $debugger;

    public function __construct(Debugger $debugger)
    {
        $this->debugger = $debugger;
    }

    public function expandEntities(string $val): string
    {
        if ($this->charencoding) {
            $val = str_replace('&', '&amp;', $val);
            $val = str_replace("'", '&apos;', $val);
            $val = str_replace('"', '&quot;', $val);
            $val = str_replace('<', '&lt;', $val);
            $val = str_replace('>', '&gt;', $val);
        }
        return $val;
    }

    public function isArraySimpleOrStruct(array $val): string
    {
        $keyList = array_keys($val);
        foreach ($keyList as $keyListValue) {
            if (!is_int($keyListValue)) {
                return 'arrayStruct';
            }
        }
        return 'arraySimple';
    }
}
