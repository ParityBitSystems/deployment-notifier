<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;
use ParityBit\DeploymentNotifier\Deployment;

class CodebaseClient
{
    protected $curl;
    protected $codebaseProjectName;
    protected $repositoryName;
	protected $headers = [
        'Accept' => 'application/xml',
        'Content-type' => 'application/xml',
    ];

	const WEBSERVICE_URL = 'http://api3.codebasehq.com/';

    public function __construct(Curl $curl, $username, $password, $codebaseProjectName, $repositoryName = null)
    {
        $this->curl = $curl;
        $this->codebaseProjectName = $codebaseProjectName;
        $this->repositoryName = $repositoryName;
        foreach ($this->headers as $header => $value) {
            $this->curl->setHeader($header, $value);
        }

        $this->curl->setBasicAuthentication($username, $password);
    }

    public function getTicketsFromIds($ticketIds = [])
    {
        $this->curl->get(
            self::WEBSERVICE_URL . $this->codebaseProjectName . '/tickets',
            [
                'query' => 'id' . implode(',', $ticketIds)
            ]
        );

        if ($this->curl->error) {
            return [];
        }
        else {
            $tickets = simplexml_load_string($this->curl->response);
            $completed_ticket_names = [];
            foreach($tickets as $ticket) {
                $completed_ticket_names[] = (string) $ticket->summary;
            }

            return $completed_ticket_names;
        }
    }

    public function updateTicketStatus($project, $ticketId, $newStatus)
    {
        $payload = "<ticket-note>
    <changes>
        <status-id>" . $newStatus . "</status-id>
    </changes>
</ticket-note>";

        $this->curl->post(
            self::WEBSERVICE_URL . $this->codebaseProjectName . '/tickets/' . $ticketId . '/notes',
            $payload
        );
    }

    public function registerDeployment(Deployment $deployment)
    {
        $payload = "<deployment>
  <revision>" . (string) $deployment->getCurrentVersion() . "</revision>
  <environment>" . (string) $deployment->getEnvironment() . "</environment>
  <servers>" . (string) $deployment->getServer() . "</servers>
</deployment>";

        $this->curl->post(
            self::WEBSERVICE_URL . $this->codebaseProjectName . '/' . $this->repositoryName . '/deployments',
            $payload
        );
    }
}
