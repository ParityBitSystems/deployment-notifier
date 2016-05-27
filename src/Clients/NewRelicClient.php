<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;

class NewRelicClient
{
    protected $curl;
    protected $appName;

    const NOTIFICATION_URL = 'https://api.newrelic.com/deployments.xml';

    public function __construct(Curl $curl, $apiKey, $appName)
    {
        $this->curl = $curl;
        $this->curl->setHeader('x-api-key', $apiKey);
        $this->appName = $appName;
    }

    public function logDeployment()
    {
        // SHOULDDO: Use API v2
        $this->curl->post(
            self::NOTIFICATION_URL,
            'deployment[app_name]=' . $this->appName
        );
    }
}
