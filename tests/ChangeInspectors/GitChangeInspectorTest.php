<?php

namespace ParityBit\DeploymentNotifier\ChangeInspectors;

use ParityBit\DeploymentNotifier\Change;
use ParityBit\DeploymentNotifier\Deployment;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitChangeInspectorTest extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();

        $this->gitChangeInspector = new GitChangeInspector(new ProcessBuilder(), '');

        $this->deployment = $this->getMockBuilder(Deployment::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getPreviousVersion', 'getCurrentVersion'])
                           ->getMock();

        $this->process = $this->getMockBuilder(Process::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['run', 'isSuccessful', 'getOutput', 'getErrorOutput',])
                        ->getMock();

        $this->builder = $this->getMockBuilder(ProcessBuilder::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['setPrefix', 'getProcess', 'add',])
                        ->getMock();

    }

    public function testGetGitLogFromDeploymentWithoutVersions()
    {
        $this->process->expects($this->once())->method('run');
        $this->process->expects($this->once())->method('isSuccessful')->will($this->returnValue(true));
        $this->process->expects($this->once())->method('getOutput')->will($this->returnValue($this->getGitLog()));

        $this->builder->expects($this->never())->method('add');
        $this->builder->expects($this->once())->method('getProcess')->will($this->returnValue($this->process));

        $this->gitChangeInspector = new GitChangeInspector($this->builder, '');
        $out = $this->gitChangeInspector->getGitLogFromDeployment($this->deployment);

        $this->assertEquals($this->getGitLog(), $out);
    }

    public function testGetGitLogFromDeploymentWithPreviousVersion()
    {
        $previousVersion = $this->faker->word;
        $this->deployment->expects($this->atLeastOnce())
                         ->method('getPreviousVersion')
                         ->will($this->returnValue($previousVersion));

        $this->process->expects($this->once())->method('run');
        $this->process->expects($this->once())->method('isSuccessful')->will($this->returnValue(true));
        $this->process->expects($this->once())->method('getOutput')->will($this->returnValue($this->getGitLog()));

        $this->builder->expects($this->never())->method('add');
        $this->builder->expects($this->once())->method('getProcess')->will($this->returnValue($this->process));

        $this->gitChangeInspector = new GitChangeInspector($this->builder, '');
        $out = $this->gitChangeInspector->getGitLogFromDeployment($this->deployment);

        $this->assertEquals($this->getGitLog(), $out);
    }

    public function testGetGitLogFromDeploymentWithVersions()
    {
        $previousVersion = $this->faker->word;
        $currentVersion = $this->faker->word;

        $revisionRange = $previousVersion . '..' . $currentVersion;

        $this->deployment->expects($this->atLeastOnce())
                         ->method('getPreviousVersion')
                         ->will($this->returnValue($previousVersion));

        $this->deployment->expects($this->atLeastOnce())
                         ->method('getCurrentVersion')
                         ->will($this->returnValue($currentVersion));

        $this->process->expects($this->once())->method('run');
        $this->process->expects($this->once())->method('isSuccessful')->will($this->returnValue(true));
        $this->process->expects($this->once())->method('getOutput')->will($this->returnValue($this->getGitLog()));

        $this->builder->expects($this->once())->method('add')->with($this->equalTo($revisionRange));
        $this->builder->expects($this->once())->method('getProcess')->will($this->returnValue($this->process));

        $this->gitChangeInspector = new GitChangeInspector($this->builder, '');
        $out = $this->gitChangeInspector->getGitLogFromDeployment($this->deployment);

        $this->assertEquals($this->getGitLog(), $out);
    }

    public function testGetFitLogFromDeploymentThowsExceptionOnFailure()
    {
        $this->process->expects($this->once())->method('run');
        $this->process->expects($this->atLeastOnce())->method('isSuccessful')->will($this->returnValue(false));

        $this->builder->expects($this->once())->method('getProcess')->will($this->returnValue($this->process));

        $this->gitChangeInspector = new GitChangeInspector($this->builder, '');

        $this->setExpectedException(ProcessFailedException::class);

        $out = $this->gitChangeInspector->getGitLogFromDeployment($this->deployment);
    }

    public function testGetChangesFromDeployment()
    {
        $this->process->expects($this->once())->method('run');
        $this->process->expects($this->once())->method('isSuccessful')->will($this->returnValue(true));
        $this->process->expects($this->once())->method('getOutput')->will($this->returnValue($this->getGitLog()));

        $this->builder->expects($this->once())->method('getProcess')->will($this->returnValue($this->process));

        $this->gitChangeInspector = new GitChangeInspector($this->builder, '');
        $out = $this->gitChangeInspector->getChangesFromDeployment($this->deployment);

        $this->assertEquals($this->getChanges(), $out);
    }

    public function testConvertGitLogIntoChanges()
    {
        $gitLog = $this->getGitLog();
        $expectedChanges = $this->getChanges();
        $actualChanges = $this->gitChangeInspector->convertGitLogIntoChanges($gitLog);
        $this->assertEquals($expectedChanges, $actualChanges);
    }

    protected function getGitLog()
    {
        return "commit a57aa5cc0400caba828eb52590624d22dd635419
Merge: 2278a5b d46e0a7
Author: Michael Peacock <mkpeacock@gmail.com>
Date:   Tue May 24 11:10:52 2016 +0100

    Merged: Some Merge

commit 2278a5b5850d65b34b630f854ae26f8157af8117
Author: Michael Peacock <mkpeacock@gmail.com>
Date:   Fri May 20 14:50:52 2016 +0100

    Some commit

    [completed: 1029]";
    }

    protected function getChanges()
    {
        $changes = [];

        $change = new Change();
        $change->reference = "a57aa5cc0400caba828eb52590624d22dd635419";
        $change->author = "Michael Peacock <mkpeacock@gmail.com>";
        $change->summary = "Merged: Some Merge";
        $change->fullDescription = "Merged: Some Merge";
        $changes[] = $change;

        $change = new Change();
        $change->reference = "2278a5b5850d65b34b630f854ae26f8157af8117";
        $change->author = "Michael Peacock <mkpeacock@gmail.com>";
        $change->summary = "Some commit";
        $change->fullDescription = "Some commit
[completed: 1029]";
        $changes[] = $change;

        return $changes;
    }
}
