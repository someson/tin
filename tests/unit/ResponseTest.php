<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Someson\TIN\Client as TinClient;
use Someson\TIN\Params as TinParams;
use Someson\TIN\Response as TinResponse;
use Someson\TIN\Exceptions\{ UnexpectedValueException, LengthException, RuntimeException };

class ResponseTest extends Unit
{
    /** @var \UnitTester */
    protected $tester;

    /** @var TinClient */
    protected $tinClient;

    /** @var Response */
    protected $response;

    /** @var \ReflectionProperty */
    protected $clientProperty;

    /**
     * @throws \ReflectionException
     */
    protected function _before()
    {
        $this->tinClient = new TinClient();
        $this->response = new Response(200);

        $reflection = new \ReflectionClass($this->tinClient);
        $this->clientProperty = $reflection->getProperty('_httpClient');
        $this->clientProperty->setAccessible(true);
    }

    public function testResponseCanThrowExceptions(): void
    {
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody(Utils::streamFor(''))])
        );
        $this->tester->expectThrowable(LengthException::class, function() {
            $this->tinClient->request('any', [], 'de');
        });

        $body = Utils::streamFor('not empty, but not the expected xml pattern');
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $this->tester->expectThrowable(UnexpectedValueException::class, function() {
            $this->tinClient->request('any', [], 'de');
        });

        $body = Utils::streamFor($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $this->tester->expectThrowable(RuntimeException::class, function() {
            $this->tinClient->request('any', [], 'zz');
        });
    }

    public function testShouldReturnResponse(): void
    {
        $body = Utils::streamFor($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->verify(new TinParams([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]));

        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(TinResponse::class, $response);
        $this->assertEquals($response->getType(), $response::SIMPLE);
    }

    public function testMustHandleUnknownReturnCode(): void
    {
        $body = Utils::streamFor($this->_validResponseFixture(0));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');
        $this->assertEquals('[Unknown return code]', $response->getMessage());
    }

    public function testTranslationMustBeLoaded(): void
    {
        $body = Utils::streamFor($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');
        $this->assertEquals('Die angefragte USt-IdNr. [SK987654321] ist gültig.', $response->getMessage());

        $response = $this->tinClient->request('any', [], 'en');
        $this->assertEquals('The requested VAT ID [SK987654321] is valid.', $response->getMessage());
    }

    public function testShouldBeValid(): void
    {
        $body = Utils::streamFor($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');
        $this->assertEquals(true, $response->isValid());
    }

    public function testCommonMessageShouldBeReturned(): void
    {
        $body = Utils::streamFor($this->_validResponseFixture(213));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');
        $this->assertEquals(false, $response->isValid());
        $this->assertEquals('Die Validierung der Umsatzsteuer-Identifikationsnummer ist fehlgeschlagen. Bitte prüfe deine Eingabe oder versuche es zu einem späteren Zeitpunkt noch einmal.', $response->getMessage());

        $response = $this->tinClient->request('any', [], 'en');
        $this->assertEquals(false, $response->isValid());
        $this->assertEquals('The validation of the VAT identification number failed. Please check your entry or try again later.', $response->getMessage());
    }

    public function _validResponseFixture(int $code): string
    {
        return require sprintf('%s/response_%u.php', FIXTURES, $code);
    }
}
