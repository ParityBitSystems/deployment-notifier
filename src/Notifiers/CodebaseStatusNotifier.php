<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Clients\CodebaseClient;
use ParityBit\DeploymentNotifier\Deployment;

/**
 * A notifier to change the status of codebase tickets e.g. mark them as 'deployed'
 */
class CodebaseStatusNotifier implements Notifier
{
    protected $client;

    public function __construct(CodebaseClient $client)
    {
        $this->client = $client;
    }

    public function notifyOfDeployment(Deployment $deployment)
    {

    }
}
