<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Codeception\Util\HttpCode;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Someson\TIN\Client as TinClient;
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
        $this->response = new Response(HttpCode::OK);

        $reflection = new \ReflectionClass($this->tinClient);
        $this->clientProperty = $reflection->getProperty('_httpClient');
        $this->clientProperty->setAccessible(true);
    }

    public function testResponseCanThrowExceptions(): void
    {
        $body = \GuzzleHttp\Psr7\stream_for();
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $this->tester->expectThrowable(LengthException::class, function() {
            $this->tinClient->request('any', [], 'de');
        });

        $body = \GuzzleHttp\Psr7\stream_for('not empty, but not the expected xml pattern');
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $this->tester->expectThrowable(UnexpectedValueException::class, function() {
            $this->tinClient->request('any', [], 'de');
        });

        $body = \GuzzleHttp\Psr7\stream_for($this->_validResponseFixture(200));
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
        $body = \GuzzleHttp\Psr7\stream_for($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');

        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(TinResponse::class, $response);
    }

    public function testTranslationMustBeLoaded(): void
    {
        $body = \GuzzleHttp\Psr7\stream_for($this->_validResponseFixture(200));
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
        $body = \GuzzleHttp\Psr7\stream_for($this->_validResponseFixture(200));
        $this->clientProperty->setValue(
            $this->tinClient,
            $this->createConfiguredMock(GuzzleClient::class, ['request' => $this->response->withBody($body)])
        );
        $response = $this->tinClient->request('any', [], 'de');
        $this->assertEquals(true, $response->isValid());
    }

    public function _validResponseFixture(int $code): string
    {
        /** @noinspection PhpIncludeInspection */
        return require sprintf('%s/response_%u.php', FIXTURES, $code);
    }
}
