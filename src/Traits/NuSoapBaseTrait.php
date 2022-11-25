<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapBaseTrait
{
    private function isObject(mixed $val, string $use): string
    {
        $this->debugger->debug('serializeVal: serialize soapval');
        $xml = $val->serialize($use);
        $this->debugger->appendDebug($val->getDebug());
        $val->debugger->clearDebug();
        $this->debugger->debug('serializeVal of soapval returning ' . $xml);
        return $xml;
    }
    private function formatName(mixed $name, mixed $nameNs): string
    {
        $localName = 'noname';
        if (is_numeric($name)) {
            $localName = '__numeric_' . $name;
        }
        if ($nameNs) {
            $localName = $this->prefix . ':' . $localName;
        }
        return $localName;
    }

    private function formatXmlns(mixed $nameNs, mixed $typeNs): string
    {
        $xmlns = '';
        if ($nameNs) {
            $xmlns .= ' xmlns:' . $this->prefix . '="' . $nameNs . '" ';
        }
        if ($typeNs) {
            $xmlns .= 'xmlns:' . $this->typePrefix . '="' . $typeNs . '" ';
        }
        return $xmlns;
    }

    private function formatTypePrefix(mixed $typeNs): bool
    {
        if ($typeNs !== '' && $typeNs === $this->getNamespaces('xsd')) {
            $this->typePrefix = 'xsd';
            return true;
        }
        $this->typePrefix = 'ns' . rand(1000, 9999);
        return true;
    }

    private function serializeAttributes(array $attr): string
    {
        $result = '';
        if (!empty($attr)) {
            foreach ($attr as $k => $v) {
                $result .= ' ' . $k . '="' . $this->expandEntities($v) . '" ';
            }
        }
        return $result;
    }
}
