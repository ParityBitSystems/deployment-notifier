<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Clients\SlackClient;
use ParityBit\DeploymentNotifier\Deployment;

/**
 * A notifier to tell slack there has been a deployment
 */
class SlackNotifier implements Notifier
{
    protected $client;

    public function __construct(SlackClient $client)
    {
        $this->client = $client;
    }

    public function notifyOfDeployment(Deployment $deployment)
    {
        $this->client->sendMessage();
    }
}
