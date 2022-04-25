<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 13:55
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: DHLRequest.php
 */


namespace Txtech\Express\Posts\DHL;

use GuzzleHttp\Psr7\Request;
use Txtech\Express\Core\HttpClient\HttpClient;
use Txtech\Express\Core\HttpClient\HttpClientException;
use Txtech\Express\Posts\PostApiException;

/**
 * Class DHLRequest
 * @package Txtech\Express\Posts\DHL
 */
class DHLRequest
{
    /** @var HttpClient */
    protected HttpClient $client;

    /** @var string */
    protected string $endPoint;

    /** @var string */
    protected string $url;

    /** @var string */
    protected string $username;

    /** @var string */
    protected string $password;

    /** @var string[] */
    protected array $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->client = new HttpClient();

        $this->url = $options['url'];
        $this->username = $options['username'];
        $this->password = $options['password'];
    }

    /**
     * @param array $body
     * @return mixed
     * @throws PostApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function shipmentRequest(array $body): mixed
    {
        $this->endPoint = '/ShipmentRequest';

        try {
            $response = $this->client->doRequest(new Request(
                'POST',
                $this->url . $this->endPoint,
                $this->getRequestHeaders(),
                json_encode($body)
            ));

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            } else {
                throw new PostApiException($response->getBody());
            }
        } catch (HttpClientException $e) {
            throw new PostApiException($e->getMessage());
        }
    }


    /**
     * @return string[]
     */
    protected function getRequestHeaders(): array
    {
        return $this->headers + $this->basicAuthHeader();
    }

    /**
     * @return string[]
     */
    protected function basicAuthHeader(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
        ];
    }
}