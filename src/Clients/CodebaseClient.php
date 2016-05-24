<?php

namespace ParityBit\DeploymentNotifier\Clients;

use ParityBit\DeploymentNotifier\Deployment;

class CodebaseClient
{
    protected $username;
	protected $password;
	protected $headers = [
        'Accept: application/xml',
        'Content-type: application/xml',
    ];

	const WEBSERVICE_URL = 'http://api3.codebasehq.com/';

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getTicketsFromIds($ticketIds = [])
    {

    }

    public function updateTicketStatus($project, $ticketId, $newStatus)
    {

    }

    public function registerDeployment(Deployment $deployment)
    {

    }
}
