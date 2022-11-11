<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapProperties
{
    /**
     * Identification for HTTP headers.
     *
     * @var string
     */
    private string $title = 'NuSOAP';
    /**
     * Version for HTTP headers.
     *
     * @var string
     */
    private string $version = '0.9.5';
    /**
     * CVS revision for HTTP headers.
     *
     * @var string
     */
    private string $revision = '$Revision: 1.123 $';
    /**
     * Toggles automatic encoding of special characters as entities
     * (should always be true, I think)
     *
     * @var bool
     */
    private bool $charencoding = true;
    /**
     * Set schema version
     *
     * @var string
     */
    private string $xmlSchemaVersion = 'http://www.w3.org/2001/XMLSchema';
    /**
     * Charset encoding for outgoing messages
     *
     * @var string
     */
    private string $soapDefEncoding = 'ISO-8859-1';
    /**
     * Namespaces in an array of prefix => uri
     * this is "seeded" by a set of constants, but it may be altered by code
     *
     * @var array
     */
    private array $namespaces = [
        'SOAP-ENV' => 'http://schemas.xmlsoap.org/soap/envelope/',
        'xsd' => 'http://www.w3.org/2001/XMLSchema',
        'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        'SOAP-ENC' => 'http://schemas.xmlsoap.org/soap/encoding/',
    ];
    /**
     * Namespaces used in the current context, e.g. during serialization
     *
     * @var array
     */
    private array $usedNamespaces = [];
    /**
     * XML Schema types in an array of uri => (array of xml type => php type)
     * is this legacy yet?
     * no, this is used by the nusoap_xmlschema class to verify type => namespace mappings.
     *
     * @var array
     */
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
    /**
     * XML entities to convert
     *
     * @var array
     */
    private array $xmlEntities = [
        'quot' => '"',
        'amp' => '&',
        'lt' => '<',
        'gt' => '>',
        'apos' => "'",
    ];

    /**
     * Argument from
     * class NuSoapBase
     * method serializeVal
     *
     * $value
     *
     * @var mixed
     */
    private mixed $value;
    private string $name;
    private string $type;
    private string $nameNs;
    private string $typeNs;
    private array $attributes;
    private string $use;
    private bool $soapval;

}
