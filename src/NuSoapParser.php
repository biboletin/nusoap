<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\NuSoapBase;
use Biboletin\Nusoap\Traits\ParserGetters;
use Biboletin\Nusoap\Traits\ParserSetters;
use Biboletin\Nusoap\Traits\ParserProperties;

class NuSoapParser extends NuSoapBase
{
    use ParserProperties;
    use ParserSetters;
    use ParserGetters;

    public function __construct(
        string $xml = '',
        string $encoding = 'UTF-8',
        string $method = '',
        bool $decodeUtf8 = true
    ) {
        parent::__construct(new Debugger());
        $this->setXml($xml);
        $this->setXmlEncoding($encoding);
        $this->setMethod($method);
        $this->setDecodeUtf8($decodeUtf8);
    }
}
