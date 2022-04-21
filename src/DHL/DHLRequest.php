<?php

namespace TxTech\Express\DHL;

use GuzzleHttp\Psr7\Request;
use TxTech\Express\HttpClient\Exception\HttpClientException;
use TxTech\Express\HttpClient\GuzzleClient;
use QueueTrait;

/**
 * Class DHLRequest
 * @package TxTech\Express\DHL
 */
class DHLRequest
{
    use QueueTrait;

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var string 功能端点
     */
    protected $endPoint;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    protected $headers = [
        'Content-Type' => 'application/json',
    ];

    public function __construct()
    {
        $this->client = GuzzleClient::getInstance();

        $this->url = get_env('DHL_EXPRESS_REST_URL');

        $this->username = get_env('DHL_EXPRESS_REST_USERNAME');

        $this->password = get_env('DHL_EXPRESS_REST_PASSWORD');
    }

    /**
     * @param array $body
     * @return bool|mixed
     * @throws \Exception
     */
    public function ShipmentRequest(array $body)
    {
        $this->endPoint = '/ShipmentRequest';

        try {
            $response = $this->client->doRequest(new Request(
                'POST',
                $this->url . $this->endPoint,
                $this->getRequestHeaders(),
                json_encode($body)
            ));
        } catch (HttpClientException $exception) {
            $this->printToLogFile("info", "DHL面单生成失败", $exception->getMessage(), true);
        }

        if ($response->getStatusCode() === 200) {
            return json_decode((string)$response->getBody(), true);
        } else {
            $this->printToLogFile("info", "DHL面单生成失败", (string)$response->getBody(), true);

            throw new \Exception('面单生成失败: ' . (string)$response->getBody());
        }
    }

    /**
     * @param array $body
     * @return bool|mixed
     * @throws \Exception
     */
    public function pickUpRequest(array $body)
    {
        $this->endPoint = '/PickupRequest';

        try {
            $response = $this->client->doRequest(new Request(
                'POST',
                $this->url . $this->endPoint,
                $this->getRequestHeaders(),
                json_encode($body)
            ));
        } catch (HttpClientException $exception) {
            $this->printToLogFile("info", "DHL取件请求失败", $exception->getMessage(), true);
        }

        if ($response->getStatusCode() !== 200) {
            $this->printToLogFile("info", "DHL取件请求失败", (string)$response->getBody(), true);
        }

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * 设置权限帐号
     **/
    public function setAuthAccount($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @return string[]
     */
    protected function basicAuthHeader()
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
        ];
    }

    /**
     * @return string[]
     */
    protected function getRequestHeaders()
    {
        return $this->headers + $this->basicAuthHeader();
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger()
    {
        return $this->client->getLogger();
    }
}