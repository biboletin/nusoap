<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapGetters
{
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function getCharEncoding(): bool
    {
        return $this->charencoding;
    }

    public function getXmlSchemaVersion(): string
    {
        return $this->xmlSchemaVersion;
    }

    public function getSoapDefEncoding(): string
    {
        return $this->soapDefEncoding;
    }

    /**
     * GetNamespaces
     *
     * @param string $key
     *
     * @return string|array
     */
    public function getNamespaces(string $key)
    {
        if ($key !== '') {
            return $this->namespaces[$key];
        }
        return $this->namespaces;
    }

    /**
     * GetUsedNamespaces
     *
     * @param string $key
     *
     * @return string|array
     */
    public function getUsedNamespaces(string $key)
    {
        if ($key !== '') {
            return $this->usedNamespaces[$key];
        }
        return $this->usedNamespaces;
    }

    /**
     * GetTypemap
     *
     * @param string $key
     *
     * @return string|array
     */
    public function getTypemap(string $key)
    {
        if ($key !== '') {
            return $this->typemap[$key];
        }
        return $this->typemap;
    }

    /**
     * GetXmlEntities
     *
     * @param string $key
     *
     * @return string|array
     */
    public function getXmlEntities(string $key)
    {
        if ($key !== '') {
            return $this->xmlEntities[$key];
        }
        return $this->xmlEntities;
    }
}
