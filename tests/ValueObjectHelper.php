<?php

namespace ParityBit\DeploymentNotifier;

abstract class ValueObjectHelper extends \PHPUnit_Framework_TestCase
{
    protected $objectUnderTest;
    protected $value;

    public function testGetReference()
    {
        $this->assertEquals($this->value, $this->objectUnderTest->getReference());
    }

    public function testToString()
    {
        $value = (string) $this->objectUnderTest;
        $this->assertEquals($this->value, $value);
    }

    protected function generateRandomValue()
    {
        $faker = \Faker\Factory::create();
        $this->value = $faker->word;
    }
}
