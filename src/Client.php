<?php

namespace Someson\TIN;

use \GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\{ ConnectException, GuzzleException };

final class Client
{
    public const BASEURI = 'https://evatr.bff-online.de';

    /** @var GuzzleClient */
    private $_httpClient;

    /**
     * Client constructor.
     * @param string|null $baseUri
     */
    public function __construct(?string $baseUri = null)
    {
        $this->_httpClient = new GuzzleClient([
            'base_uri' => $baseUri ?? self::BASEURI,
        ]);
    }

    /**
     * @param string $endpoint
     * @param string[] $query
     * @param string $lang
     * @return Response
     * @throws GuzzleException|ConnectException
     */
    public function request(string $endpoint, array $query, string $lang): Response
    {
        $params = $query ? ['query' => $query] : [];
        $response = $this->_httpClient->request('GET', $endpoint, $params);
        if ($response->getStatusCode() === 200 && $body = trim((string) $response->getBody())) {
            return new Response($body, $lang);
        }
        throw new Exceptions\LengthException('Response unprocessable');
    }

    /**
     * @param Params $params
     * @param string $lang
     * @return Response
     * @throws GuzzleException
     */
    public function verify(Params $params, string $lang = 'de'): Response
    {
        return $this->request('/evatrRPC', $params->getCollection(), $lang);
    }
}
