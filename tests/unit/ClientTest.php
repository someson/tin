<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Codeception\Util\HttpCode;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Someson\TIN\Params;
use Someson\TIN\Client as TinClient;
use Someson\TIN\Exceptions\{ UnexpectedValueException, LengthException, DomainException };

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

    public function testParamsCanThrowExceptions(): void
    {
        $this->tester->expectThrowable(UnexpectedValueException::class, function() {
            new Params([]);
        });
        $this->tester->expectThrowable(DomainException::class, function() {
            new Params([
                'UstId_1' => 'DE123456789',
                'UstId_2' => 'DE789456132',
                'Druck' => true,
            ]);
        });
    }

    public function testParamsMustBeNotEmpty(): void
    {
        $params = new Params([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]);
        $this->assertNotEmpty($params->getCollection());
    }

    public function testParamsShouldBeDescribable(): void
    {
        $params = new Params([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]);
        foreach ($params->getCollection() as $description) {
            $this->assertNotEquals('[unknown]', $description);
        }
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

        $response = new Response(HttpCode::INTERNAL_SERVER_ERROR);
        $property->setValue(
            $tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $response])
        );

        $this->tester->expectThrowable(LengthException::class, function() use ($tinClient) {
            $tinClient->request('any', [], 'de');
        });

        $response = new Response(HttpCode::OK, [], '');
        $property->setValue(
            $tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $response])
        );
        $this->tester->expectThrowable(LengthException::class, function() use ($tinClient) {
            $tinClient->request('any', [], 'de');
        });
    }
}
