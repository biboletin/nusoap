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
    private function formatName(mixed $name): string
    {
        if (is_numeric($name)) {
            return '__numeric_' . $name;
        }
        return 'noname';
    }

    private function serializeAttributes(array $attr): string
    {
        $result = '';
        if (!empty($attr)) {
            foreach ($attr as $k => $v) {
                $result .= ' ' . $k . '="' . $this->expandEntities($v) . '"';
            }
        }
        return $result;
    }
}
