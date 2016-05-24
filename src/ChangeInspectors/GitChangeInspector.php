<?php

namespace ParityBit\DeploymentNotifier\ChangeInspectors;

use ParityBit\DeploymentNotifier\Change;
use ParityBit\DeploymentNotifier\Deployment;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitChangeInspector implements ChangeInspector
{
    protected $repoDirectory;

    public function __construct($repoDirectory)
    {
        $this->repoDirectory = $repoDirectory;

        // folder exists

        // check is a git repo
    }

    public function getChangesFromDeployment(Deployment $deployment)
    {
        $gitLog = $this->getGitLogFromDeployment($deployment);

        return $this->convertGitLogIntoChanges($gitLog);
    }

    public function getGitLogFromDeployment(Deployment $deployment)
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix('git log');

        if (!is_null($deployment->getPreviousVersion())) {
            if (!is_null($deployment->getCurrentVersion())) {
                $revisionRange = $deployment->getPreviousVersion() . '..' . $deployment->getCurrentVersion();
                $builder->add($revisionRange);
            }
        }

        $process = $builder->getProcess();
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    public function convertGitLogIntoChanges($gitLog)
    {
        $changes = [];

        $gitLog = explode(\PHP_EOL, $gitLog);

        $change = new Change();
        foreach ($gitLog as $line) {
            if(strpos($line, 'commit') === 0) {
                if (!is_null($change->reference)) {
                    $change->fullDescription = trim($change->fullDescription);
                    $changes[] = $change;
                    $change = new Change();
                }
                $change->reference = trim(substr($line, 6));
            } elseif (strpos($line, 'Author:') === 0) {
                $change->author = trim(substr($line, 7));
            } elseif (preg_match('/^\s/', $line)) {
                $line = trim($line);
                if ('' != $line) {
                    if (is_null($change->summary)) {
                        $change->summary = $line;
                    }
                    $change->fullDescription .= $line . PHP_EOL;
                }
            }
        }

        $change->fullDescription = trim($change->fullDescription);
        $changes[] = $change;

        return $changes;
    }
}
