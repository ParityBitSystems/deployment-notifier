<?php

namespace ParityBit\DeploymentNotifier;

class VersionTest extends ValueObjectHelper
{
    protected $objectUnderTest;
    protected $value;

    public function setUp()
    {
        $this->generateRandomValue();
        $this->objectUnderTest = new Version($this->value);
    }
}
