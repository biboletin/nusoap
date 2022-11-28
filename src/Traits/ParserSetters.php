<?php

namespace Biboletin\Nusoap\Traits;

trait ParserSetters
{
    /**
     * SetXml
     *
     * @param string $xml
     *
     * @return void
     */
    public function setXml(string $xml): void
    {
        $this->xml = $xml;
    }
    /**
     * SetXmlEncoding
     *
     * @param string $encoding
     *
     * @return void
     */
    public function setXmlEncoding(string $encoding): void
    {
        $this->xmlEncoding = $encoding;
    }
    /**
     * SetMethod
     *
     * @param string $method
     *
     * @return void
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }
    /**
     * SetRootStruct
     *
     * @param string $struct
     *
     * @return void
     */
    public function setRootStruct(string $struct): void
    {
        $this->rootStruct = $struct;
    }
    /**
     * SetRootStructName
     *
     * @param string $name
     *
     * @return void
     */
    public function setRootStructName(string $name): void
    {
        $this->rootStructName = $name;
    }
    /**
     * SetRootStructNamespace
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setRootStructNamespace(string $namespace): void
    {
        $this->rootStructNamespace = $namespace;
    }
    /**
     * SetRootHeader
     *
     * @param string $header
     *
     * @return void
     */
    public function setRootHeader(string $header): void
    {
        $this->rootHeader = $header;
    }
    /**
     * SetDocument
     *
     * @param string $document
     *
     * @return void
     */
    public function setDocument(string $document): void
    {
        $this->document = $document;
    }
    /**
     * SetStatus
     *
     * @param string $status
     *
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    /**
     * SetDefaultNamespace
     *
     * @param stirng $namespace
     *
     * @return void
     */
    public function setDefaultNamespace(string $namespace): void
    {
        $this->defaultNamespace = $namespace;
    }
    /**
     * SetParent
     *
     * @param string $parent
     *
     * @return void
     */
    public function setParent(string $parent): void
    {
        $this->parent = $parent;
    }
    /**
     * SetFaultCode
     *
     * @param string $code
     *
     * @return void
     */
    public function setFaultCode(string $code): void
    {
        $this->faultCode = $code;
    }
    /**
     * SetFaultString
     *
     * @param string $string
     *
     * @return void
     */
    public function setFaultString(string $string): void
    {
        $this->faultString = $string;
    }
    /**
     * SetFaultDetail
     *
     * @param string $detail
     *
     * @return void
     */
    public function setFaultDetail(string $detail): void
    {
        $this->faultDetail = $detail;
    }
    /**
     * SetResponseHeaders
     *
     * @param string $headers
     *
     * @return void
     */
    public function setResponseHeaders(string $headers): void
    {
        $this->responseHeaders = $headers;
    }
    /**
     * SetPosition
     *
     * @param int $position
     *
     * @return void
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
    /**
     * SetDepth
     *
     * @param int $depth
     *
     * @return void
     */
    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }
    /**
     * SetBodyPosition
     *
     * @param int $position
     *
     * @return void
     */
    public function setBodyPosition(int $position): void
    {
        $this->bodyPosition = $position;
    }
    /**
     * SetSoapResponse
     *
     * @param mixed $response
     *
     * @return void
     */
    public function setSoapResponse(mixed $response): void
    {
        $this->soapresponse = $response;
    }
    /**
     * SetSoapHeader
     *
     * @param mixed $header
     *
     * @return void
     */
    public function setSoapHeader(mixed $header): void
    {
        $this->soapheader = $header;
    }
    /**
     * SetNamespaces
     *
     * @param array $namespaces
     *
     * @return void
     */
    public function setParserNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
    /**
     * SetMessage
     *
     * @param array $message
     *
     * @return void
     */
    public function setMessage(array $message): void
    {
        $this->message = $message;
    }
    /**
     * SetDepthArray
     *
     * @param array $depth
     *
     * @return void
     */
    public function setDepthArray(array $depth): void
    {
        $this->depthArray = $depth;
    }
    /**
     * SetIds
     *
     * @param array $ids
     *
     * @return void
     */
    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }
    /**
     * SetMultiRefs
     *
     * @param array $refs
     *
     * @return void
     */
    public function setMultiRefs(array $refs): void
    {
        $this->multirefs = $refs;
    }
    /**
     * SetFault
     *
     * @param bool $fault
     *
     * @return void
     */
    public function setFault(bool $fault): void
    {
        $this->fault = $fault;
    }
    /**
     * SetDebugFlag
     *
     * @param bool $flag
     *
     * @return void
     */
    public function setDebugFlag(bool $flag): void
    {
        $this->debugFlag = $flag;
    }
    /**
     * SetDecodeUtf8
     *
     * @param bool $flag
     *
     * @return void
     */
    public function setDecodeUtf8(bool $flag): void
    {
        $this->decode_utf8 = $flag;
    }
}
