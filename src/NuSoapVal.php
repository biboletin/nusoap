<?php

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\NuSoap;
use Biboletin\Nusoap\Debugger;

class NuSoapVal extends NuSoap
{
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
}
