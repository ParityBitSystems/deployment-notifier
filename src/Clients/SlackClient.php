<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;

class SlackClient
{
    protected $curl;
    protected $webhookUrl;
    protected $channels = [];
    protected $iconUrl = null;
    protected $username = null;

    public function __construct(Curl $curl, $webhookUrl, $channels = [], $username = 'Deployment Notifier', $iconUrl = null)
    {
        $this->curl = $curl;
        $this->webhookUrl = $webhookUrl;
        $this->channels = $channels;
        $this->username = $username;
        $this->iconUrl = $iconUrl;
    }

    public function sendMessage($message)
    {
        foreach ($this->channels as $channel) {
            $payload = $this->getPayload($message, $channel);

            $this->curl->post(
                $this->webhookUrl,
                'payload=' . json_encode($payload)
            );
        }
    }

    public function getPayload($message, $channel)
    {
        $payload = [];

        if (!is_null($this->iconUrl)) {
            $payload['icon_url'] = $this->iconUrl;
        }

        $payload['text'] = $message;
        $payload['channel'] = $channel;
        $payload['username'] = $this->username;

        return $payload;
    }
}
