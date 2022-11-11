<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapSetters
{
    /**
     * SetTitle
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * SetVersion
     *
     * @param string $version
     *
     * @return void
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * SetRevision
     *
     * @param string $revision
     *
     * @return void
     */
    public function setRevision(string $revision): void
    {
        $this->revision = $revision;
    }

    /**
     * SetCharEncoding
     *
     * @param bool $charencoding
     *
     * @return void
     */
    public function setCharEncoding(bool $charencoding): void
    {
        $this->charencoding = $charencoding;
    }

    /**
     * SetXmlSchemaVersion
     *
     * @param string $xmlSchemaVersion
     *
     * @return void
     */
    public function setXmlSchemaVersion(string $xmlSchemaVersion): void
    {
        $this->xmlSchemaVersion = $xmlSchemaVersion;
    }

    /**
     * SetSoapDefEncoding
     *
     * @param string $soapDefEncoding
     *
     * @return void
     */
    public function setSoapDefEncoding(string $soapDefEncoding): void
    {
        $this->soapDefEncoding = $soapDefEncoding;
    }

    /**
     * SetNameSpaces
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setNameSpaces(string $key, string $value): void
    {
        $this->namespaces[$key] = $value;
    }

    /**
     * SetUsedNamespaces
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setUsedNamespaces(string $key, string $value): void
    {
        $this->usedNamespaces[$key] = $value;
    }

    /**
     * SetTypeMap
     *
     * @param array<string|mixed> $typemap
     *
     * @return void
     */
    public function setTypeMap(array $typemap): void
    {
        $this->typemap[] = $typemap;
    }

    /**
     * SetXmlEntities
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setXmlEntities(string $key, string $value): void
    {
        $this->xmlEntities[$key] = $value;
    }

    /**
     * SetValue
     *
     * @param mixed $value
     *
     * @return void
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
        $this->debugger->appendDebug('value=' . $this->debugger->varDump($value));
    }
}
