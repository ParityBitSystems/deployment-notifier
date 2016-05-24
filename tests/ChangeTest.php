<?php

namespace ParityBit\DeploymentNotifier;

class ChangeTest extends \PHPUnit_Framework_TestCase
{
    protected $change;
    protected $faker;

    public function setUp()
    {
        $this->change = new Change();
        $this->faker = \Faker\Factory::create();
    }

    public function testToString()
    {
        $this->change->summary = $this->faker->word;
        $this->change->fullDescription = $this->faker->sentence;
        $this->change->author = $this->faker->word;
        $this->change->reference = $this->faker->word;

        $this->assertEquals($this->change->summary, (string) $this->change);
    }
}
