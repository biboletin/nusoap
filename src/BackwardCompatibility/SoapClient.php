<?php

namespace Biboletin\Nusoap\BackwardCompatibility;

use Biboletin\Nusoap\NuSoapClient;

if (!extension_loaded('soap')) {
    /**
     * For backwards compatiblity, define soapclient unless the PHP SOAP extension is loaded.
     */
    class SoapClient extends NuSoapClient
    {
    }
}
