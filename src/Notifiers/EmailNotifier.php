<?php

namespace ParityBit\DeploymentNotifier\Notifiers;

use ParityBit\DeploymentNotifier\Deployment;

/**
 * A notifier to tell stakholders there have been changes made to a particular
 * environment as the result of a deployment
 *
 */
class EmailNotifier implements Notifier
{
    public function __construct()
    {

    }

    public function notifyOfDeployment(Deployment $deployment)
    {

    }
}
