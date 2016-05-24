<?php

namespace ParityBit\DeploymentNotifier;

class EnvironmentTest extends ValueObjectHelper
{
    protected $objectUnderTest;
    protected $value;

    public function setUp()
    {
        $this->generateRandomValue();
        $this->objectUnderTest = new Environment($this->value);
    }
}
