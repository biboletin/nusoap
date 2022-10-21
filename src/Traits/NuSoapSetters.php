<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapSetters
{
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function setRevision(string $revision): void
    {
        $this->revision = $revision;
    }

    public function setCharEncoding(bool $charencoding): void
    {
        $this->charencoding = $charencoding;
    }
    public function setXmlSchemaVersion(string $xmlSchemaVersion): void
    {
        $this->xmlSchemaVersion = $xmlSchemaVersion;
    }
    public function setSoapDefEncoding(string $soapDefEncoding): void
    {
        $this->soapDefEncoding = $soapDefEncoding;
    }

    public function setNameSpaces(string $key, string $value): void
    {
        $this->namespaces[$key] = $value;
    }

    public function setUsedNamespaces(string $key, string $value): void
    {
        $this->usedNamespaces[$key] = $value;
    }

    public function setTypeMap(array $typemap): void
    {
        $this->typemap[] = $typemap;
    }

    public function setXmlEntities(string $key, string $value): void
    {
        $this->xmlEntities[$key] = $value;
    }
}
