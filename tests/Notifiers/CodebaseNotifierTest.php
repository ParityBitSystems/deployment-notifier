<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Clients\CodebaseClient;
use ParityBit\DeploymentNotifier\Deployment;

class CodebaseNotifierTest extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function testNotifyOfDeployment()
    {
        $deployment = $this->getMockBuilder(Deployment::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $client = $this->getMockBuilder(CodebaseClient::class)
                       ->disableOriginalConstructor()
                       ->setMethods(['registerDeployment'])
                       ->getMock();

        $client->expects($this->once())
               ->method('registerDeployment')
               ->with($this->equalTo($deployment));

        $notifier = new CodebaseNotifier($client);
        $notifier->notifyOfDeployment($deployment);
    }
}
