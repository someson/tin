<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Someson\TIN\Client as TinClient;
use Someson\TIN\Exceptions\LengthException;

class ClientTest extends Unit
{
    /** @var \UnitTester */
    protected $tester;

    /**
     * @throws \ReflectionException
     */
    public function testShouldHaveEndpoint(): void
    {
        $tinClient = new TinClient();
        $reflection = new \ReflectionClass($tinClient);
        $property = $reflection->getProperty('_httpClient');
        $property->setAccessible(true);
        $endpoint = $property->getValue($tinClient);

        $this->assertEquals(TinClient::BASEURI, $endpoint->getConfig('base_uri'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testRequestCanThrowException(): void
    {
        $tinClient = new TinClient();
        $reflection = new \ReflectionClass($tinClient);
        $property = $reflection->getProperty('_httpClient');
        $property->setAccessible(true);

        $response = new Response(500);
        $property->setValue(
            $tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $response])
        );
        $this->tester->expectThrowable(LengthException::class, function() use ($tinClient) {
            $tinClient->request('any', [], 'de');
        });

        $response = new Response(200, [], '');
        $property->setValue(
            $tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $response])
        );
        $this->tester->expectThrowable(LengthException::class, function() use ($tinClient) {
            $tinClient->request('any', [], 'de');
        });
    }
}
