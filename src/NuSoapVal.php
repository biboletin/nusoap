<?php

/**
 * For creating serializable abstractions of native PHP types.  This class
 * allows element name/namespace, XSD type, and XML attributes to be
 * associated with a value.  This is extremely useful when WSDL is not
 * used, but is also useful when WSDL is used with polymorphic types, including
 * xsd:anyType and user-defined types.
 *
 * @author  Dietrich Ayala <dietrich@ganx4.com>
 * @version $Id: class.soap_val.php,v 1.11 2007/04/06 13:56:32 snichol Exp $
 */

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\NuSoap;
use Biboletin\Nusoap\Debugger;

class NuSoapVal extends NuSoap
{
    /**
     * Constructor
     *
     * @param string $name optional name
     * @param mixed $type optional type name
     * @param mixed $value optional value
     * @param mixed $elementNs optional namespace of value
     * @param mixed $typeNs optional namespace of type
     * @param mixed $attributes associative array of attributes to add to element serialization
     */
    public function __construct(
        private string $name,
        private string $type,
        private string $value,
        private string $elementNs,
        private string $typeNs,
        private array $attributes
    ) {
        parent::__construct(new Debugger());
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->elementNs = $elementNs;
        $this->typeNs = $typeNs;
        $this->attributes = $attributes;
    }

    /**
     * Return serialized value
     *
     * @param string $use The WSDL use value (encoded|literal)
     *
     * @return string XML data
     */
    public function serialize(string $use = 'encoded'): string
    {
        $xml = $this->serializeVal(
            $this->value,
            $this->name,
            $this->type,
            $this->elementNs,
            $this->typeNs,
            $this->attributes,
            $use,
            true
        );
        return $xml;
    }

    /**
     * Decodes a soapval object into a PHP native type
     *
     * @return mixed
     */
    public function decode(): mixed
    {
        return $this->value;
    }
}
