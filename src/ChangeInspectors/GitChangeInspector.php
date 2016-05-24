<?php

namespace ParityBit\DeploymentNotifier\ChangeInspectors;

use ParityBit\DeploymentNotifier\Deployment;

class GitChangeInspector implements ChangeInspector
{
    protected $repoDirectory;

    public function __construct($repoDirectory)
    {
        $this->repoDirectory = $repoDirectory;


    }

    public function getChangesFromDeployment(Deployment $deployment)
    {

    }
}
