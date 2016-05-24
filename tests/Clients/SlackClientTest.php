<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;

class SlackClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();

        $this->curl = $this->getMockBuilder(Curl::class)
                           ->setMethods(['post', 'get'])
                           ->getMock();

        $this->webhookUrl = $this->faker->url;
        $this->channels = [
            $this->faker->word,
            $this->faker->word,
        ];
        $this->username = $this->faker->word;
        $this->iconUrl = $this->faker->imageUrl(640, 480);

        $this->buildClient();
    }

    protected function buildClient()
    {
        $this->client = new SlackClient(
            $this->curl,
            $this->webhookUrl,
            $this->channels,
            $this->username,
            $this->iconUrl
        );
    }

    public function testGetPayloadWithNoIconUrl()
    {
        $this->iconUrl = null;

        $this->buildClient();

        $message = $this->faker->word;
        $channel = $this->faker->word;

        $payload = [
            'text' => $message,
            'channel' => $channel,
            'username' => $this->username,
        ];

        $this->assertEquals(
            $payload,
            $this->client->getPayload($message, $channel)
        );
    }

    public function testGetPayload()
    {
        $message = $this->faker->word;
        $channel = $this->faker->word;

        $payload = [
            'icon_url' => $this->iconUrl,
            'text' => $message,
            'channel' => $channel,
            'username' => $this->username,
        ];

        $this->assertEquals(
            $payload,
            $this->client->getPayload($message, $channel)
        );
    }

    public function testSendMessage()
    {
        $message = $this->faker->word;
        $i = 0;
        foreach ($this->channels as $channel) {
            $payload = [
                'icon_url' => $this->iconUrl,
                'text' => $message,
                'channel' => $channel,
                'username' => $this->username,
            ];
            $payload = 'payload=' . json_encode($payload);

            $this->curl->expects($this->at($i))
                       ->method('post')
                       ->with(
                            $this->equalTo($this->webhookUrl),
                            $this->equalTo($payload)
                        );

            $i++;
        }

        $this->client->sendMessage($message);
    }
}
