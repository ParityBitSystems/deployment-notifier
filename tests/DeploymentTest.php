<?php

namespace ParityBit\DeploymentNotifier;

class DeploymentTest extends \PHPUnit_Framework_TestCase
{
    protected $faker;
    protected $previousVersion;
    protected $currentVersion;
    protected $environment;
    protected $server;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        $this->previousVersion = new Version($this->faker->word);
        $this->currentVersion = new Version($this->faker->word);
        $this->environment = new Environment($this->faker->word);
        $this->server = new Server($this->faker->word);

        $this->deployment = new Deployment(
            $this->environment,
            $this->previousVersion,
            $this->currentVersion,
            $this->server
        );
    }

    public function testGetEnvironment()
    {
        $this->assertEquals(
            $this->environment,
            $this->deployment->getEnvironment()
        );
    }

    public function testGetPreviousVersion()
    {
        $this->assertEquals(
            $this->previousVersion,
            $this->deployment->getPreviousVersion()
        );
    }

    public function testGetCurrentVersion()
    {
        $this->assertEquals(
            $this->currentVersion,
            $this->deployment->getCurrentVersion()
        );
    }

    public function testGetServer()
    {
        $this->assertEquals(
            $this->server,
            $this->deployment->getServer()
        );
    }
}
