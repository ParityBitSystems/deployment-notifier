<?php

namespace ParityBit\DeploymentNotifier;

class ServerTest extends ValueObjectHelper
{
    protected $objectUnderTest;
    protected $value;

    public function setUp()
    {
        $this->generateRandomValue();
        $this->objectUnderTest = new Server($this->value);
    }
}
