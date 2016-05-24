<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Clients\CodebaseClient;
use ParityBit\DeploymentNotifier\Deployment;

/**
 * A notifier to tell codebase there has been a deployment
 */
class CodebaseNotifier implements Notifier
{
    protected $client;

    public function __construct(CodebaseClient $client)
    {
        $this->client = $client;
    }

    public function notifyOfDeployment(Deployment $deployment)
    {
        $this->client->registerDeployment($deployment);
    }
}
