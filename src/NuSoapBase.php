<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\Traits\NuSoapGetters;
use Biboletin\Nusoap\Traits\NuSoapSetters;
use Biboletin\Nusoap\Traits\NuSoapBaseTrait;
use Biboletin\Nusoap\Traits\NuSoapProperties;
use Biboletin\Nusoap\Traits\NuSoapSerialize;

class NuSoapBase
{
    use NuSoapProperties;
    use NuSoapSetters;
    use NuSoapGetters;
    use NuSoapBaseTrait;
    use NuSoapSerialize;

    /**
     * $prefix
     *
     * @var string
     */
    private string $prefix = 'nu';
    /**
     * $typePrefix
     *
     * @var string
     */
    private string $typePrefix = '';

    /**
     * __construct
     *
     * @param private
     */
    public function __construct(private Debugger $debugger)
    {
        $this->prefix .= rand(1000, 9999);
    }

    /**
     * ExpandEntities
     *
     * @param string $val
     *
     * @return string
     */
    private function expandEntities(string $val): string
    {
        if ($this->getCharEncoding()) {
            $val = str_replace('&', '&amp;', $val);
            $val = str_replace("'", '&apos;', $val);
            $val = str_replace('"', '&quot;', $val);
            $val = str_replace('<', '&lt;', $val);
            $val = str_replace('>', '&gt;', $val);
        }
        return $val;
    }

    /**
     * IsArraySimpleOrStruct
     *
     * @param mixed $val
     *
     * @return string
     */
    private function isArraySimpleOrStruct(mixed $val): string
    {
        $keyList = array_keys($val);
        foreach ($keyList as $keyListValue) {
            if (!is_int($keyListValue)) {
                return 'arrayStruct';
            }
        }
        return 'arraySimple';
    }
    /**
     * SerializeVal
     *
     * @param mixed $val
     * @param mixed $name
     * @param mixed $type
     * @param mixed $nameNs
     * @param mixed $typeNs
     * @param array $attributes
     * @param string $use
     * @param boolean $soapval
     *
     * @return void
     */
    public function serializeVal(
        mixed $val,
        mixed $name,
        mixed $type,
        mixed $nameNs,
        mixed $typeNs,
        array $attributes,
        string $use,
        bool $soapval
    ) {
        $this->debugger->debug('in serializeVal: name=' . $name);
        $this->debugger->debug('in serializeVal: type=' . $type);
        $this->debugger->debug('in serializeVal: name_ns=' . $nameNs);
        $this->debugger->debug('in serializeVal: type_ns=' . $typeNs);
        $this->debugger->debug('in serializeVal: use=' . $use);
        $this->debugger->debug('in serializeVal: soapval=' . $soapval);
        $this->debugger->appendDebug('value=' . $this->debugger->varDump($val));
        $this->debugger->appendDebug('attributes=' . $this->debugger->varDump($attributes));
        if (is_object($val) && get_class($val) === 'soapval' && (!$soapval)) {
            return $this->isObject($val, $use);
        }
        // force valid name if necessary
        $name = $this->formatName($name, $nameNs);
        $this->formatTypePrefix($typeNs);
        $xmlns = $this->formatXmlns($nameNs, $typeNs);
        // serialize attributes if present
        $atts = $this->serializeAttributes($attributes);
        // serialize null value
        if ($val === null) {
            $this->debugger->debug('serializeVal: serialize null');
            return $this->nullableValue($use, $name, $xmlns, $atts, $type);
        }
        // serialize if an xsd built-in primitive type
        if ($type !== '' && $this->getTypemap($this->getXmlSchemaVersion($type)) !== null) {
            $this->debugger->debug('serializeVal: serialize xsd built-in primitive type');
            return $this->serializeXsdPrimitiveType($val, $type, $use, $name, $xmlns, $atts);
        }
        // detect type and serialize
        $xml = $this->detectAndSerialize($val, $type, $typeNs, $name, $xmlns, $atts, $use, $soapval);
        $this->debugger->debug('serializeVal returning ' . $xml);
        return $xml;
    }
    /**
     * DetectAndSerialize
     *
     * @param mixed $val
     * @param mixed $type
     * @param mixed $typeNs
     * @param mixed $name
     * @param string $xmlns
     * @param string $atts
     * @param string $use
     * @param boolean $soapval
     *
     * @return string
     */
    private function detectAndSerialize(
        mixed $val,
        mixed $type,
        mixed $typeNs,
        mixed $name,
        string $xmlns,
        string $atts,
        string $use,
        bool $soapval
    ): string {
        $xml = '';
        switch (true) {
            case (is_bool($val) || $type === 'boolean'):
                $this->debugger->debug('serializeVal: serialize boolean');
                $xml = $this->serializeBoolean($val, $type, $use, $name, $xmlns, $atts);
                break;
            case (is_int($val) || is_long($val) || $type === 'int'):
                $this->debugger->debug('serializeVal: serialize int');
                $xml = $this->serializeInteger($val, $use, $name, $xmlns, $atts);
                break;
            case (is_float($val) || is_double($val) || $type == 'float'):
                $this->debugger->debug('serializeVal: serialize float');
                $xml = $this->serializeFloat($val, $use, $name, $xmlns, $atts);
                break;
            case (is_string($val) || $type == 'string'):
                $this->debugger->debug('serializeVal: serialize string');
                $val = $this->expandEntities($val);
                $xml = $this->serializeString($val, $use, $name, $xmlns, $atts);
                break;
            case is_object($val):
                $this->debugger->debug('serializeVal: serialize object');
                $xml = $this->serializeObject($val, $name, $type, $use, $soapval, $xmlns, $atts);
                break;
            case (is_array($val) || $type):
                // detect if struct or array
                $valueType = $this->isArraySimpleOrStruct($val);
                $xml = $this->serializeArray($valueType, $val, $name, $type, $typeNs, $use, $xmlns, $atts);
                break;
            default:
                $this->debugger->debug('serializeVal: serialize unknown');
                $xml = 'not detected, got ' . gettype($val) . ' for ' . $val;
                break;
        }
        return $xml;
    }
}
