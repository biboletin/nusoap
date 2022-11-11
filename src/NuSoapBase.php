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

    public function __construct(private Debugger $debugger)
    {
    }

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

    public function serializeVal(
        mixed $val,
        string $name,
        string $type,
        string $nameNs,
        string $typeNs,
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
        $name = $this->formatName($name);
        // if name has ns, add ns prefix to name
        $xmlns = '';
        if ($nameNs !== '') {
            $prefix = 'nu' . rand(1000, 9999);
            $name = $prefix . ':' . $name;
            $xmlns .= ' xmlns:' . $prefix . '="' . $nameNs . '"';
        }
        // if type is prefixed, create type prefix
        if ($typeNs !== '' && $typeNs === $this->getNamespaces('xsd')) {
            // need to fix this. shouldn't default to xsd if no ns specified
            // w/o checking against typemap
            $type_prefix = 'xsd';
        } elseif ($typeNs !== '') {
            $type_prefix = 'ns' . rand(1000, 9999);
            $xmlns .= ' xmlns:' . $type_prefix . '="' . $typeNs . '"';
        }
        // serialize attributes if present
        $atts = $this->serializeAttributes($attributes);
        // serialize null value
        if ($val === null) {
            $this->debugger->debug('serializeVal: serialize null');
            if ($use === 'literal') {
                // TODO: depends on minOccurs
                $xml = '<' . $name . $xmlns . $atts . '>';
                $this->debugger->debug('serializeVal returning ' . $xml);
                return $xml;
            } else {
                if (isset($type) && isset($type_prefix)) {
                    $type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
                } else {
                    $type_str = '';
                }
                $xml = '<' . $name . $xmlns . $type_str . $atts . ' xsi:nil="true">';
                $this->debugger->debug('serializeVal returning ' . $xml);
                return $xml;
            }
        }
        // serialize if an xsd built-in primitive type
        if ($type !== '' && $this->getTypemap($this->getXmlSchemaVersion($type)) !== null) {
            $this->debugger->debug('serializeVal: serialize xsd built-in primitive type');
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
                $xml = '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
                $this->debugger->debug('serializeVal returning ' . $xml);
                return $xml;
            } else {
                $xml = '<' . $name . $xmlns . ' xsi:type="xsd:' . $type . '" ' . $atts . '>' . $val .
                    '</' . $name . '>';
                $this->debugger->debug('serializeVal returning ' . $xml);
                return $xml;
            }
        }
        // detect type and serialize
        $xml = '';
        switch (true) {
            case (is_bool($val) || $type === 'boolean'):
                $this->debugger->debug('serializeVal: serialize boolean');
                if ($type === 'boolean') {
                    $val = $val ? 'true' : 'false';
                } elseif (!$val) {
                    $val = 0;
                }
                if ($use === 'literal') {
                    $xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
                } else {
                    $xml .= '<' . $name . $xmlns . ' xsi:type="xsd:boolean" ' .
                        $atts . '>' . $val . '</' . $name . '>';
                }
                break;
            case (is_int($val) || is_long($val) || $type === 'int'):
                $this->debugger->debug('serializeVal: serialize int');
                if ($use === 'literal') {
                    $xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
                } else {
                    $xml .= '<' . $name . $xmlns . ' xsi:type="xsd:int" ' . $atts . '>' . $val . '</' . $name . '>';
                }
                break;
            case (is_float($val) || is_double($val) || $type == 'float'):
                $this->debugger->debug('serializeVal: serialize float');
                if ($use === 'literal') {
                    $xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
                } else {
                    $xml .= '<' . $name . $xmlns . ' xsi:type="xsd:float" ' . $atts . '>' . $val . '</' . $name . '>';
                }
                break;
            case (is_string($val) || $type == 'string'):
                $this->debugger->debug('serializeVal: serialize string');
                $val = $this->expandEntities($val);
                if ($use === 'literal') {
                    $xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
                } else {
                    $xml .= '<' . $name . $xmlns . ' xsi:type="xsd:string" ' . $atts . '>' . $val . '</' . $name . '>';
                }
                break;
            case is_object($val):
                $this->debugger->debug('serializeVal: serialize object');
                if (get_class($val) === 'soapval') {
                    $this->debugger->debug('serializeVal: serialize soapval object');
                    $pXml = $val->serialize($use);
                    $this->debugger->appendDebug($val->getDebug());
                    $val->debugger->clearDebug();
                } else {
                    if ($name !== '') {
                        $name = get_class($val);
                        $this->debugger->debug('In serializeVal, used class name ' . $name . ' as element name');
                    } else {
                        $this->debugger->debug('In serializeVal, do not override name ' .
                            $name . ' for element name for class ' . get_class($val));
                    }
                    foreach (get_object_vars($val) as $k => $v) {
                        $pXml = isset($pXml)
                            ? $pXml . $this->serializeVal($v, $k, false, false, false, [], $use, $soapval)
                            : $this->serializeVal($v, $k, false, false, false, [], $use, $soapval);
                    }
                }
                if (isset($type) && isset($type_prefix)) {
                    $type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
                } else {
                    $type_str = '';
                }
                if ($use === 'literal') {
                    $xml .= '<' . $name . $xmlns . $atts . '>' . $pXml . '</' . $name . '>';
                } else {
                    $xml .= '<' . $name . $xmlns . $type_str . $atts . '>' . $pXml . '</' . $name . '>';
                }
                break;
            case (is_array($val) || $type):
                // detect if struct or array
                $valueType = $this->isArraySimpleOrStruct($val);
                if ($valueType === 'arraySimple' || preg_match('/^ArrayOf/', $type)) {
                    $this->debugger->debug('serializeVal: serialize array');
                    $i = 0;
                    if (is_array($val) && count($val) > 0) {
                        foreach ($val as $v) {
                            if (is_object($v) && get_class($v) ===  'soapval') {
                                $tt_ns = $v->type_ns;
                                $tt = $v->type;
                            } elseif (is_array($v)) {
                                $tt = $this->isArraySimpleOrStruct($v);
                            } else {
                                $tt = gettype($v);
                            }
                            $array_types[$tt] = 1;
                            // TODO: for literal, the name should be $name
                            $xml .= $this->serializeVal($v, 'item', false, false, false, [], $use, $soapval);
                            ++$i;
                        }
                        if (count($array_types) > 1) {
                            $array_typename = 'xsd:anyType';
                        } elseif (isset($tt) && $this->getTypemap($this->getXmlSchemaVersion($tt)) !== null) {
                            if ($tt === 'integer') {
                                $tt = 'int';
                            }
                            $array_typename = 'xsd:' . $tt;
                        } elseif (isset($tt) && $tt === 'arraySimple') {
                            $array_typename = 'SOAP-ENC:Array';
                        } elseif (isset($tt) && $tt === 'arrayStruct') {
                            $array_typename = 'unnamed_struct_use_soapval';
                        } else {
                            // if type is prefixed, create type prefix
                            if ($tt_ns !== '' && $tt_ns === $this->getNamespaces('xsd')) {
                                $array_typename = 'xsd:' . $tt;
                            } elseif ($tt_ns) {
                                $tt_prefix = 'ns' . rand(1000, 9999);
                                $array_typename = $tt_prefix . ':' . $tt;
                                $xmlns .= ' xmlns:' . $tt_prefix . '="' . $tt_ns . '"';
                            } else {
                                $array_typename = $tt;
                            }
                        }
                        $array_type = $i;
                        if ($use === 'literal') {
                            $type_str = '';
                        } elseif (isset($type) && isset($type_prefix)) {
                            $type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
                        } else {
                            $type_str = ' xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="' .
                                $array_typename . '"[' . $array_type . ']"';
                        }
                        // empty array
                    } else {
                        if ($use === 'literal') {
                            $type_str = '';
                        } elseif (isset($type) && isset($type_prefix)) {
                            $type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
                        } else {
                            $type_str = ' xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="xsd:anyType[0]"';
                        }
                    }
                    // TODO: for array in literal, there is no wrapper here
                    $xml = '<' . $name . $xmlns . $type_str . $atts . '>' . $xml . '</' . $name . '>';
                } else {
                    // got a struct
                    $this->debugger->debug('serializeVal: serialize struct');
                    if (isset($type) && isset($type_prefix)) {
                        $type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
                    } else {
                        $type_str = '';
                    }
                    if ($use === 'literal') {
                        $xml .= '<' . $name . $xmlns . $atts . '>';
                    } else {
                        $xml .= '<' . $name . $xmlns . $type_str . $atts . '>';
                    }
                    foreach ($val as $k => $v) {
                        // Apache Map
                        if ($type === 'Map' && $typeNs === 'http://xml.apache.org/xml-soap') {
                            $xml .= '<item>';
                            $xml .= $this->serializeVal($k, 'key', false, false, false, [], $use, $soapval);
                            $xml .= $this->serializeVal($v, 'value', false, false, false, [], $use, $soapval);
                            $xml .= '</item>';
                        } else {
                            $xml .= $this->serializeVal($v, $k, false, false, false, [], $use, $soapval);
                        }
                    }
                    $xml .= '</' . $name . '>';
                }
                break;
            default:
                $this->debugger->debug('serializeVal: serialize unknown');
                $xml .= 'not detected, got ' . gettype($val) . ' for ' . $val;
                break;
        }
        $this->debugger->debug('serializeVal returning ' . $xml);
error_log('XML: ' . $xml);
        return $xml;
    }
}
