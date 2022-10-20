<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapProperties
{
    private string $title = 'NuSOAP';
    private string $version = '0.9.5';
    private string $revision = '$Revision: 1.123 $';
    private bool $charencoding = true;
    private string $xmlSchemaVersion = 'http://www.w3.org/2001/XMLSchema';
    private string $soapDefEncoding = 'ISO-8859-1';
    private array $namespaces = [
        'SOAP-ENV' => 'http://schemas.xmlsoap.org/soap/envelope/',
        'xsd' => 'http://www.w3.org/2001/XMLSchema',
        'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        'SOAP-ENC' => 'http://schemas.xmlsoap.org/soap/encoding/',
    ];
    private array $usedNamespaces = [];
    private array $typemap = [
        'http://www.w3.org/2001/XMLSchema' => [
            'string' => 'string',
            'boolean' => 'boolean',
            'float' => 'double',
            'double' => 'double',
            'decimal' => 'double',
            'duration' => '',
            'dateTime' => 'string',
            'time' => 'string',
            'date' => 'string',
            'gYearMonth' => '',
            'gYear' => '',
            'gMonthDay' => '',
            'gDay' => '',
            'gMonth' => '',
            'hexBinary' => 'string',
            'base64Binary' => 'string',
            // abstract "any" types
            'anyType' => 'string',
            'anySimpleType' => 'string',
            // derived datatypes
            'normalizedString' => 'string',
            'token' => 'string',
            'language' => '',
            'NMTOKEN' => '',
            'NMTOKENS' => '',
            'Name' => '',
            'NCName' => '',
            'ID' => '',
            'IDREF' => '',
            'IDREFS' => '',
            'ENTITY' => '',
            'ENTITIES' => '',
            'integer' => 'integer',
            'nonPositiveInteger' => 'integer',
            'negativeInteger' => 'integer',
            'long' => 'integer',
            'int' => 'integer',
            'short' => 'integer',
            'byte' => 'integer',
            'nonNegativeInteger' => 'integer',
            'unsignedLong' => '',
            'unsignedInt' => '',
            'unsignedShort' => '',
            'unsignedByte' => '',
            'positiveInteger' => '',
        ],
        'http://www.w3.org/2000/10/XMLSchema' => [
            'i4' => '',
            'int' => 'integer',
            'boolean' => 'boolean',
            'string' => 'string',
            'double' => 'double',
            'float' => 'double',
            'dateTime' => 'string',
            'timeInstant' => 'string',
            'base64Binary' => 'string',
            'base64' => 'string',
            'ur-type' => 'array',
        ],
        'http://www.w3.org/1999/XMLSchema' => [
            'i4' => '',
            'int' => 'integer',
            'boolean' => 'boolean',
            'string' => 'string',
            'double' => 'double',
            'float' => 'double',
            'dateTime' => 'string',
            'timeInstant' => 'string',
            'base64Binary' => 'string',
            'base64' => 'string',
            'ur-type' => 'array',
        ],
        'http://soapinterop.org/xsd' => [
            'SOAPStruct' => 'struct',
        ],
        'http://schemas.xmlsoap.org/soap/encoding/' => [
            'base64' => 'string',
            'array' => 'array',
            'Array' => 'array',
        ],
        'http://xml.apache.org/xml-soap' => [
            'Map',
        ],
    ];
    private array $xmlEntities = [
        'quot' => '"',
        'amp' => '&',
        'lt' => '<',
        'gt' => '>',
        'apos' => "'",
    ];
}
