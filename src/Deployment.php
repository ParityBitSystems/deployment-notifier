<?php

namespace ParityBit\DeploymentNotifier;

use ParityBit\DeploymentNotifier\ChangeInspectors\ChangeInspector;

class Deployment
{
    protected $environment;
    protected $previousVersion;
    protected $currentVersion;
    protected $changes = null;
    protected $server;
    protected $changeInspector;

    public function __construct(
        Environment $environment,
        Version $previousVersion,
        Version $currentVersion,
        Server $server,
        ChangeInspector $changeInspector)
    {
        $this->environment = $environment;
        $this->previousVersion = $previousVersion;
        $this->currentVersion = $currentVersion;
        $this->server = $server;
        $this->changeInspector = $changeInspector;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getPreviousVersion()
    {
        return $this->previousVersion;
    }

    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getChanges()
    {
        if (is_null($this->changes)) {
            $this->changes = $this->changeInspector->getChangesFromDeployment($this);
        }

        return $this->changes;
    }
}
