<?php

namespace Biboletin\Nusoap\Traits;

trait ParserProperties
{
    /**
     * $xml
     *
     * @var string
     */
    private string $xml;
    /**
     * $xmlEncoding
     *
     * @var string
     */
    private string $xmlEncoding;
    /**
     * $method
     *
     * @var string
     */
    private string $method;
    /**
     * $rootStruct
     *
     * @var string
     */
    private string $rootStruct;
    /**
     * $rootStructName
     *
     * @var string
     */
    private string $rootStructName;
    /**
     * $rootStructNamespace
     *
     * @var string
     */
    private string $rootStructNamespace;
    /**
     * $rootHeader
     *
     * @var string
     */
    private string $rootHeader;
    /**
     * $document
     *
     * @var string
     */
    private string $document;
    /**
     * $status
     *
     * @var string
     */
    private string $status;
    /**
     * $defaultNamespace
     *
     * @var string
     */
    private string $defaultNamespace;
    /**
     * $parent
     *
     * @var string
     */
    private string $parent;
    /**
     * $faultCode
     *
     * @var string
     */
    private string $faultCode;
    /**
     * $faultString
     *
     * @var string
     */
    private string $faultString;
    /**
     * $faultDetail
     *
     * @var string
     */
    private string $faultDetail;
    /**
     * $responseHeaders
     *
     * @var string
     */
    private string $responseHeaders;
    /**
     * $position
     *
     * @var int
     */
    private int $position;
    /**
     * $depth
     *
     * @var int
     */
    private int $depth;
    /**
     * $bodyPosition
     *
     * @var int
     */
    private int $bodyPosition;
    /**
     * $soapresponse
     *
     * @var mixed
     */
    private mixed $soapresponse;
    /**
     * $soapheader
     *
     * @var mixed
     */
    private mixed $soapheader;
    /**
     * $namespaces
     *
     * @var array
     */
    private array $namespaces;
    /**
     * $message
     *
     * @var array
     */
    private array $message;
    /**
     * $depthArray
     *
     * @var array
     */
    private array $depthArray;
    /**
     * $ids
     *
     * @var array
     */
    private array $ids;
    /**
     * $multirefs
     *
     * @var array
     */
    private array $multirefs;
    /**
     * $fault
     *
     * @var bool
     */
    private bool $fault = false;
    /**
     * $debugFlag
     *
     * @var bool
     */
    private bool $debugFlag = true;
    /**
     * $decode_utf8
     *
     * @var bool
     */
    private bool $decode_utf8 = true;
}
