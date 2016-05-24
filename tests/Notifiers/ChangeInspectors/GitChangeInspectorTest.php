<?php

namespace ParityBit\DeploymentNotifier\ChangeInspectors;

use ParityBit\DeploymentNotifier\Change;

class GitChangeInspectorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gitChangeInspector = new GitChangeInspector('');
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
