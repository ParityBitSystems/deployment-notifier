<?php

namespace ParityBit\DeploymentNotifier;

class Deployment
{
    protected $environment;
    protected $previousVersion;
    protected $currentVersion;
    protected $changes = null;
    protected $server;

    public function __construct(
        Environment $environment,
        Version $previousVersion,
        Version $currentVersion,
        Server $server)
    {
        $this->environment = $environment;
        $this->previousVersion = $previousVersion;
        $this->currentVersion = $currentVersion;
        $this->server = $server;
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

    }
}
