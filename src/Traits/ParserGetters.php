<?php

namespace Biboletin\Nusoap\Traits;

trait ParserGetters
{
    /**
     * GetXml
     *
     * @return string
     */
    public function getXml(): string
    {
        return $this->xml;
    }
    /**
     * GetXmlEncoding
     *
     * @return string
     */
    public function getXmlEncoding(): string
    {
        return $this->xmlEncoding;
    }
    /**
     * GetMethod
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    /**
     * GetRootStruct
     *
     * @return string
     */
    public function getRootStruct(): string
    {
        return $this->rootStruct;
    }
    /**
     * GetRootStructName
     *
     * @return string
     */
    public function getRootStructName(): string
    {
        return $this->rootStructName;
    }
    /**
     * GetRootStructNamespace
     *
     * @return string
     */
    public function getRootStructNamespace(): string
    {
        return $this->rootStructNamespace;
    }
    /**
     * GetRootHeader
     *
     * @return string
     */
    public function getRootHeader(): string
    {
        return $this->rootHeader;
    }
    /**
     * GetDocument
     *
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }
    /**
     * GetStatus
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * GetDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return $this->defaultNamespace;
    }
    /**
     * GetParent
     *
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }
    /**
     * GetFaultCode
     *
     * @return string
     */
    public function getFaultCode(): string
    {
        return $this->faultCode;
    }
    /**
     * GetFaultString
     *
     * @return string
     */
    public function getFaultString(): string
    {
        return $this->faultString;
    }
    /**
     * GetFaultDetail
     *
     * @return string
     */
    public function getFaultDetail(): string
    {
        return $this->faultDetail;
    }
    /**
     * GetResponseHeaders
     *
     * @return string
     */
    public function getResponseHeaders(): string
    {
        return $this->responseHeaders;
    }
    /**
     * GetPosition
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
    /**
     * GetDepth
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }
    /**
     * GetBodyPosition
     *
     * @return int
     */
    public function getBodyPosition(): int
    {
        return $this->bodyPosition;
    }
    /**
     * GetSoapResponse
     *
     * @return mixed
     */
    public function getSoapResponse(): mixed
    {
        return $this->soapresponse;
    }
    /**
     * GetSoapHeader
     *
     * @return mixed
     */
    public function getSoapHeader(): mixed
    {
        return $this->soapheader;
    }
    /**
     * GetNamespaces
     *
     * @return array
     */
    public function getParserNamespaces(): array
    {
        return $this->namespaces;
    }
    /**
     * GetMessage
     *
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }
    /**
     * GetDepthArray
     *
     * @return array
     */
    public function getDepthArray(): array
    {
        return $this->depthArray;
    }
    /**
     * GetIds
     *
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }
    /**
     * GetMultiRefs
     *
     * @return array
     */
    public function getMultiRefs(): array
    {
        return $this->multirefs;
    }
    /**
     * GetFault
     *
     * @return bool
     */
    public function getFault(): bool
    {
        return $this->fault;
    }
    /**
     * GetDebugFlag
     *
     * @return bool
     */
    public function getDebugFlag(): bool
    {
        return $this->debugFlag;
    }
    /**
     * GetDecodeUtf8
     *
     * @return bool
     */
    public function getDecodeUtf8(): bool
    {
        return $this->decode_utf8;
    }
}
