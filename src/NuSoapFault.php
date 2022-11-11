<?php

/**
 * Contains information for a SOAP fault.
 * Mainly used for returning faults from deployed functions
 * in a server instance.
 *
 * @author  Dietrich Ayala <dietrich@ganx4.com>
 * @version $Id: class.soap_fault.php,v 1.14 2007/04/11 15:49:47 snichol Exp $
 */

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuSoapBase;

class NuSoapFault extends NuSoapBase
{
    /**
     * Constructor
     *
     * @param string $faultcode (SOAP-ENV:Client | SOAP-ENV:Server)
     * @param string $faultactor only used when msg routed between multiple actors
     * @param string $faultstring human readable error message
     * @param mixed $faultdetail detail, typically a string or array of string
     */
    public function __construct(
        private string $faultcode,
        private string $faultactor,
        private string $faultstring,
        private mixed $faultdetail
    ) {
        parent::__construct(new Debugger());
    }

    /**
     * Serialize a fault
     *
     * @return string The serialization of the fault instance.
     */
    public function serialize(): string
    {
        $ns_string = '';
        foreach ($this->namespaces as $k => $v) {
            $ns_string .= "\n xmlns:" . $k . '=' . $v;
        }
        $xml = <<<XML
<?xml version="1.0" encoding="{$this->getSoapDefEncoding()}"?>
    <SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" {$ns_string}>
        <SOAP-ENV:Body>
            <SOAP-ENV:Fault>
                {$this->serializeVal($this->faultcode, 'faultcode')}
                {$this->serializeVal($this->faultactor, 'faultactor')}
                {$this->serializeVal($this->faultstring, 'faultstring')}
                {$this->serializeVal($this->faultdetail, 'detail')}
            </SOAP-ENV:Fault>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>
XML;
        return $xml;
    }
}
