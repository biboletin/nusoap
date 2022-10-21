<?php

namespace Biboletin\Nusoap\Tests;

include '../vendor/autoload.php';

use Biboletin\Nusoap\Debugger;
use PHPUnit\Framework\TestCase;
use Biboletin\Nusoap\NuSoap;

final class NuSoapTest extends TestCase
{
    protected $nusoap;
    protected $debugger;

    public function setUp(): void
    {
        $this->debugger = new Debugger();
        $this->nusoap = new NuSoap($this->debugger);
    }
    /**
     * @covers NuSoap
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Biboletin\NuSoap\NuSoap', $this->nusoap);
    }
}