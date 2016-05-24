<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Clients\NewRelicClient;
use ParityBit\DeploymentNotifier\Deployment;

class NewRelicNotifier implements Notifier
{
    protected $client = $client;

    public function __construct(NewRelicClient $client)
    {
        $this->client = $client;
    }

    public function notifyOfDeployment(Deployment $deployment)
    {

    }
}
