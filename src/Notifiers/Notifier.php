<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Deployment;

interface Notifier
{
    /**
     * Notify the service of a deployment
     *
     * @param Deployment $deployment the deployment that was made
     *
     * @return null
     */
    public function notifyOfDeployment(Deployment $deployment);
}
