<?php

namespace Biboletin\Nusoap\Traits;

trait NuSoapSerialize
{
    private function nullableValue(string $use, string $name, string $xmlns, string $attributes, mixed $type): string
    {
        if ($use === 'literal') {
            $xml = '<' . $name . $xmlns . $attributes . '>';
            $this->debugger->debug('serialize_val returning ' . $xml);
            return $xml;
        }

        $typeString = ($type !== '' && $this->typePrefix !== '')
            ? ' xsi:type="' . $this->typePrefix . ':' . $type . '"'
            : '';
        $xml = '<' . $name . $xmlns . $typeString . $attributes . ' xsi:nil="true"/>';
        return $xml;
    }

    private function serializeXsdPrimitiveType(
        mixed $val,
        mixed $type,
        string $use,
        string $name,
        string $xmlns,
        string $attributes
    ): string {
        if (is_bool($val)) {
            if ($type === 'boolean') {
                $val = $val ? 'true' : 'false';
            } elseif (!$val) {
                $val = 0;
            }
        } elseif (is_string($val)) {
            $val = $this->expandEntities($val);
        }

        if ($use === 'literal') {
            $xml = '<' . $name . $xmlns . $attributes . '>' . $val . '</' . $name . '>';
            $this->debugger->debug('serialize_val returning ' . $xml);
            return $xml;
        }

        $xml = '<' . $name . $xmlns . 'xsi:type="xsd:' . $type . '"' . $attributes . '>' . $val . '</' . $name . '>';
        $this->debugger->debug('serialize_val returning ' . $xml);
        return $xml;
    }

    private function serializeBoolean(
        mixed $val,
        string $type,
        string $use,
        string $name,
        string $xmlns,
        string $atts
    ): string {
        $xml = '';
        if ($type === 'boolean') {
            $val = $val ? 'true' : 'false';
        } elseif (!$val) {
            $val = 0;
        }
        if ($use === 'literal') {
            $xml = '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
        }
        if ($use !== 'literal') {
            $xml = '<' . $name . $xmlns . ' xsi:type="xsd:boolean" ' . $atts . '>' . $val . '</' . $name . '>';
        }
        return $xml;
    }

    private function serializeInteger(mixed $val, string $use, string $name, string $xmlns, string $atts): string
    {
        return ($use === 'literal')
            ? '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>'
            : '<' . $name . $xmlns . ' xsi:type="xsd:int" ' . $atts . '>' . $val . '</' . $name . '>';
    }

    private function serializeFloat(mixed $val, string $use, string $name, string $xmlns, string $atts): string
    {
        return ($use === 'literal')
            ? '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>'
            : '<' . $name . $xmlns . ' xsi:type="xsd:float" ' . $atts . '>' . $val . '</' . $name . '>';
    }

    private function serializeString(mixed $val, string $use, string $name, string $xmlns, string $atts): string
    {
        return ($use === 'literal')
            ? '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>'
            : '<' . $name . $xmlns . ' xsi:type="xsd:string" ' . $atts . '>' . $val . '</' . $name . '>';
    }

    private function serializeSoapValClass(mixed $val, string $use): string
    {
        $this->debugger->debug('serializeVal: serialize soapval object');
        $val->serialize($use);
        $val->appendDebug($val->getDebug());
        $val->clearDebug();
        return $val;
    }

    private function serializeClass(mixed $name, mixed $val, string $use, bool $soapval, mixed $pxml): string
    {
        if ($name !== '') {
            $name = get_class($val);
            $this->debugger->debug('In serializeVal, used class name ' . $name . ' as element name');
        }

        if ($name === '') {
            $this->debugger->debug('In serializeVal, do not override name ' .
                $name . ' for element name for class ' . get_class($val));
        }
        $xml = '';
        foreach (get_object_vars($val) as $key => $value) {
            $xml = ($pxml !== '')
                ? $pxml . $this->serializeVal($value, $key, false, false, false, [], $use, $soapval)
                : $this->serializeVal($value, $key, false, false, false, [], $use, $soapval);
        }
        return $xml;
    }

    private function serializeObject(
        mixed $val,
        mixed $name,
        mixed $type,
        string $use,
        bool $soapval,
        string $xmlns,
        mixed $atts
    ): string {
        $pXml = (get_class($val) === 'soapval') ? $this->serializeSoapValClass($val, $use) : '';
        if (get_class($val) !== 'soapval') {
            $pXml = $this->serializeClass($name, $val, $use, $soapval, $pXml);
        }
        $typeStr = ($type && ($this->typePrefix !== '')) ? ' xsi:type="' . $this->typePrefix . ':' . $type . '"' : '';
        $xml = ($use === 'literal')
            ? '<' . $name . $xmlns . $atts . '>' . $pXml . '</' . $name . '>'
            : '<' . $name . $xmlns . $typeStr . $atts . '>' . $pXml . '</' . $name . '>';

        return $xml;
    }

    private function serializeArray(
        string $arrayType,
        mixed $val,
        string $name,
        mixed $type,
        mixed $typeNs,
        string $use,
        string $xmlns,
        string $atts
    ): string {
        return ($arrayType === 'arraySimple' || preg_match('/^ArrayOf/', $type))
            ? $this->serializeSimpleArray($val, $use, $type, $name, $xmlns, $atts)
            : $this->serializeStructArray($type, $typeNs, $use, $val, $name, $xmlns, $atts);
    }

    private function serializeSimpleArray(
        mixed $val,
        string $use,
        string $type,
        string $name,
        string $xmlns,
        string $atts
    ): string {
        $this->debugger->debug('serialize_val: serialize array');
        $counter = 0;
        $xml = '';
        $arrayTypeName = '';
        if (is_array($val) && count($val) > 0) {
            foreach ($val as $value) {
                $tt = gettype($value);
                if (is_object($value) && get_class($value) === 'soapval') {
                    $ttNs = $value->typeNs;
                    $tt = $value->type;
                } elseif (is_array($value)) {
                    $tt = $this->isArraySimpleOrStruct($value);
                }
                $arrayTypes[$tt] = 1;
                $properName = ($use === 'literal') ? $name : 'item';
                $xml .= $this->serializeVal($value, $properName, false, false, false, false, $use);
                ++$counter;
            }
            if (count($arrayTypes) > 1) {
                $arrayTypeName = 'xsd:anyType';
            } elseif ($tt !== '' && $this->typemap[$this->xmlSchemaVersion][$tt]) {
                $tt = ($tt === 'integer') ? 'int' : $tt;
                $arrayTypeName = 'xsd:' . $tt;
            } elseif ($tt !== '' && $tt === 'arraySimple') {
                $arrayTypeName = 'SOAP-ENC:Array';
            } elseif ($tt !== '' && $tt === 'arrayStruct') {
                $arrayTypeName = 'unnamed_struct_use_soapval';
            } else {
                $arrayTypeName = $tt;
                if ($ttNs !== '' && $ttNs === $this->getNamespaces('xsd')) {
                    $arrayTypeName = 'xsd:' . $tt;
                } elseif ($ttNs) {
                    $ttPrefix = 'ns' . rand(1000, 9999);
                    $arrayTypeName = $ttPrefix . ':' . $tt;
                    $xmlns .= ' xmlns:' . $ttPrefix . '="' . $ttNs . '"';
                }
            }
            $arrayType = $counter;
            $typeStr = $this->formatTypeString($type, $use, $arrayTypeName);
        }

        if (is_array($val) && count($val) === 0) {
            $typeStr = $this->formatTypeString($type, $use, $arrayTypeName);
        }
        $xml .= '<' . $name . $xmlns . $typeStr . $atts . '>' . $xml . '</' . $name . '>';
        return $xml;
    }

    private function serializeStructArray(
        mixed $type,
        mixed $typeNs,
        string $use,
        mixed $val,
        string $name,
        string $xmlns,
        string $atts,
    ): string {
        $this->debugger->debug('serialize_val: serialize struct');
        $typeStr = ($type !== false && $this->typePrefix !== '')
            ? ' xsi:type="' . $this->typePrefix . ':' . $type . '"' : '';
        $xml = ($use === 'literal')
            ? '<' . $name . $xmlns . $atts . '>'
            : '<' . $name . $xmlns . $typeStr . $atts . '>';
        foreach ($val as $key => $value) {
            if ($type === 'Map' && $typeNs === 'http://xml.apache.org/xml-soap') {
                $xml .= '<item>';
                $xml .= $this->serializeVal($key, 'key', false, false, false, false, $use);
                $xml .= $this->serializeVal($value, 'value', false, false, false, false, $use);
                $xml .= '</item>';
            }
            if ($type !== 'Map' && $typeNs !== 'http://xml.apache.org/xml-soap') {
                $xml .= '';
            }
            $xml .= '</' . $name . '>';
        }
        return $xml;
    }

    private function formatTypeString(mixed $type, string $use): string
    {
        if ($use === 'literal') {
            return '';
        }
        if ($type !== false && $this->typePrefix !== '') {
            return ' xsi:type="' . $this->typePrefix . ':' . $type . '" ';
        }
        return ' xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="xsd:anyType[0]" ';
    }
}
