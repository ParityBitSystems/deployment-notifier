<?php

namespace ParityBit\DeploymentNotifier\Clients;

use Curl\Curl;
use ParityBit\DeploymentNotifier\Environment;
use ParityBit\DeploymentNotifier\Server;
use ParityBit\DeploymentNotifier\Version;


use ParityBit\DeploymentNotifier\ChangeInspectors\ChangeInspector;
use ParityBit\DeploymentNotifier\Deployment;

class CodebaseClientTest extends \PHPUnit_Framework_TestCase
{
    protected $curl;
    protected $codebaseClient;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();

        $this->curl = $this->getMockBuilder(Curl::class)
                           ->setMethods(['post', 'get'])
                           ->getMock();

        $this->username = $this->faker->word;
        $this->password = $this->faker->word;
        $this->project = $this->faker->word;
        $this->repo = $this->faker->word;

        $this->buildClient();
    }

    protected function buildClient()
    {
        $this->codebaseClient = new CodebaseClient($this->curl, $this->username, $this->password, $this->project, $this->repo);
    }

    public function testConstructor()
    {
        $this->curl = $this->getMockBuilder(Curl::class)
                           ->setMethods(['setHeader', 'setBasicAuthentication'])
                           ->getMock();

        $this->curl->expects($this->once())->method('setBasicAuthentication')->with($this->equalTo($this->username), $this->equalTo($this->password));
        $this->curl->expects($this->exactly(2))->method('setHeader');

        $this->buildClient();
    }

    public function testGetTicketsFromIdsOnFailure()
    {
        $ticketIds = [1,3,5];
        $ticketData = ['Ticket ABCD', 'Another Ticket'];
        $url = CodebaseClient::WEBSERVICE_URL . $this->project . '/tickets';
        $payload = [
            'query' => 'id' . implode(',', $ticketIds),
        ];
        $this->curl->response = $this->ticketResponse();
        $this->curl->error = true;

        $this->buildClient();

        $data = $this->codebaseClient->getTicketsFromIds($ticketIds);
        $this->assertEquals([], $data);
    }

    public function testGetTicketsFromIds()
    {
        $ticketIds = [1,3,5];
        $ticketData = ['Ticket ABCD', 'Another Ticket'];
        $url = CodebaseClient::WEBSERVICE_URL . $this->project . '/tickets';
        $payload = [
            'query' => 'id' . implode(',', $ticketIds),
        ];
        $this->curl->response = $this->ticketResponse();

        $this->buildClient();

        $data = $this->codebaseClient->getTicketsFromIds($ticketIds);
        $this->assertEquals($ticketData, $data);
    }

    protected function ticketResponse()
    {
        return "<tickets>
            <ticket>
                <summary>Ticket ABCD</summary>
            </ticket>
            <ticket>
                <summary>Another Ticket</summary>
            </ticket>
        </tickets>";
    }

    public function testUpdateTicketStatus()
    {
        $ticketId = $this->faker->numberBetween(1, 1000);
        $newStatus = $this->faker->word;
        $this->curl->expects($this->once())
                   ->method('post')
                   ->with(
                        $this->equalTo(
                            CodebaseClient::WEBSERVICE_URL  . $this->project . '/tickets/' . $ticketId . '/notes'
                        ),
                        $this->callback(function ($subject) use ($newStatus) {
                            $data = simplexml_load_string($subject);
                            return ($newStatus == $data[0]->changes->{'status-id'});
                        })
                    );

        $this->buildClient();

        $this->codebaseClient->updateTicketStatus($ticketId, $newStatus);
    }

    public function testRegisterDeployment()
    {
        $environmentValue = $this->faker->word;
        $environment = new Environment($environmentValue);
        $previousVersion = new Version($this->faker->word);
        $currentVersionValue = $this->faker->word;
        $currentVersion = new Version($currentVersionValue);
        $serverValue = $this->faker->word;
        $server = new Server($serverValue);

        $changeInspector = $this->getMockBuilder(ChangeInspector::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $deployment = new Deployment(
            $environment,
            $previousVersion,
            $currentVersion,
            $server,
            $changeInspector
        );

        $this->curl->expects($this->once())
                   ->method('post')
                   ->with(
                        $this->equalTo(
                            CodebaseClient::WEBSERVICE_URL . $this->project . '/' . $this->repo . '/deployments'
                        ),
                        $this->callback(function ($subject) use ($currentVersionValue, $environmentValue, $serverValue) {
                            $data = simplexml_load_string($subject);

                            if (
                                $data->revision == $currentVersionValue &&
                                $data->environment == $environmentValue &&
                                $data->servers = $serverValue
                            ) {
                                return true;
                            }

                        })
                   );

        $this->buildClient();

        $this->codebaseClient->registerDeployment($deployment);
    }
}
