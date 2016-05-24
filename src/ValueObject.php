<?php

namespace ParityBit\DeploymentNotifier;

abstract class ValueObject
{
    protected $reference;

    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function __toString()
    {
        return $this->reference;
    }
}
