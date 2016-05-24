<?php

namespace ParityBit\DeploymentNotifier\Clients;

class SlackClient
{
    protected $webhookUrl;
    protected $channels = [];
    protected $iconUrl = null;
    protected $username = null;

    public function __construct($webhookUrl, $channels = [], $username = 'Deployment Notifier', $iconUrl = null)
    {
        $this->webhookUrl = $webhookUrl;
        $this->channels = $channels;
        $this->username = $username;
        $this->iconUrl = $iconUrl;
    }

    public function sendMessage($message)
    {
        foreach ($this->channels as $channel) {
            $payload = $this->getPayload($message, $channel);

            $ch = curl_init();

            curl_setopt($ch, \CURLOPT_URL, $this->webhookUrl);

            curl_setopt($ch, \CURLOPT_POST, 1);
            curl_setopt($ch, \CURLOPT_POSTFIELDS, 'payload=' .json_encode($payload));

            $result = curl_exec($ch);

            curl_close($ch);
        }
    }

    protected function getPayload($message, $channel)
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
