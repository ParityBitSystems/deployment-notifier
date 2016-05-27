<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;

class NewRelicClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $apiKey;
    protected $appName;
    protected $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();

        $this->curl = $this->getMockBuilder(Curl::class)
                           ->setMethods(['post', 'setHeader'])
                           ->getMock();

       $this->apiKey = $this->faker->uuid;
       $this->appName = $this->faker->name;

        $this->buildClient();
    }

    protected function buildClient()
    {
        $this->client = new NewRelicClient(
            $this->curl,
            $this->apiKey,
            $this->appName
        );
    }

    public function testConstructorSetsApiKeyHeader()
    {
        $this->curl->expects($this->once())->method('setHeader')->with($this->equalTo('x-api-key'), $this->equalTo($this->apiKey));
        $this->buildClient();
    }

    /**
     * @depends testConstructorSetsApiKeyHeader
     */
    public function testLogDeployment()
    {
        $this->curl->expects($this->once())
                     ->method('post')
                     ->with(
                        $this->equalTo(NewRelicClient::NOTIFICATION_URL),
                        $this->equalTo('deployment[app_name]=' . $this->appName)
                    );

        $this->buildClient();
        $this->client->logDeployment();
    }
}
