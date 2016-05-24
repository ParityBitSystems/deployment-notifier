<?php

namespace ParityBit\DeploymentNotifier;

use ParityBit\DeploymentNotifier\ChangeInspectors\ChangeInspector;

class DeploymentTest extends \PHPUnit_Framework_TestCase
{
    protected $faker;
    protected $previousVersion;
    protected $currentVersion;
    protected $environment;
    protected $server;
    protected $changeInspector;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        $this->previousVersion = new Version($this->faker->word);
        $this->currentVersion = new Version($this->faker->word);
        $this->environment = new Environment($this->faker->word);
        $this->server = new Server($this->faker->word);
        $this->changeInspector = $this->getMock(ChangeInspector::class);

        $this->deployment = new Deployment(
            $this->environment,
            $this->previousVersion,
            $this->currentVersion,
            $this->server,
            $this->changeInspector
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

    public function testGetChanges()
    {
        $changes = [
            $this->faker->word,
            $this->faker->word,
            $this->faker->word,
        ];

        $this->changeInspector = $this->getMockBuilder(ChangeInspector::class)
                                      ->disableOriginalConstructor()
                                      ->setMethods(['getChangesFromDeployment'])
                                      ->getMock();
        $this->changeInspector->expects($this->once())
                              ->method('getChangesFromDeployment')
                              ->with($this->callback(function ($subject) {
                                  return $subject == $this->deployment;
                              }))
                              ->will($this->returnValue($changes));

        $this->deployment = new Deployment(
            $this->environment,
            $this->previousVersion,
            $this->currentVersion,
            $this->server,
            $this->changeInspector
        );

        $this->assertEquals($changes, $this->deployment->getChanges());
    }
}
