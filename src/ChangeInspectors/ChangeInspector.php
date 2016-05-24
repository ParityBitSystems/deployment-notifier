<?php

namespace ParityBit\DeploymentNotifier\ChangeInspectors;

use ParityBit\DeploymentNotifier\Deployment;

interface ChangeInspector
{
    public function getChangesFromDeployment(Deployment $deployment);
}
