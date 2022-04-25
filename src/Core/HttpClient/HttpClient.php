<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 13:59
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: HttpClient.php
 */


namespace Txtech\Express\Core\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;


/**
 * Class HttpClient
 * @package Txtech\Express\Core
 */
class HttpClient
{
    /** @var Client $client */
    private Client $client;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws HttpClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function doRequest(Request $request): \Psr\Http\Message\ResponseInterface
    {
        // Initialize Guzzle, include the default options
        $guzzle = $this->getClient();
        try {
            $response = $guzzle->send($request);
        } catch (TransferException $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

}