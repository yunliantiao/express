<?php

namespace TxTech\Express\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use TxTech\Express\HttpClient\Exception\HttpClientException;

/**
 * Class GuzzleClient
 *
 */
class GuzzleClient implements ClientInterface, LoggerAwareInterface
{
    const DEFAULT_TIMEOUT = 50;
    const DEFAULT_CONNECT_TIMEOUT = 50;

    /** @var static $instance */
    protected static $instance;
    /** @var array $defaultOptions */
    protected $defaultOptions = [];
    /**
     * List of pending PSR-7 requests
     *
     * @var Request[]
     */
    protected $pendingRequests = [];
    /** @var LoggerInterface $logger */
    protected $logger;
    /** @var int $timeout */
    private $timeout = self::DEFAULT_TIMEOUT;
    /** @var int $connectTimeout */
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;
    /** @var int $maxRetries */
    private $maxRetries = 0;
    /** @var int $concurrency */
    private $concurrency = 5;
    /** @var Client $client */
    private $client;

    private $isUsedPhpBuild = false;

    /**
     * Get the Guzzle client
     *
     * @return Client
     */
    private function getClient()
    {
        if (!$this->client) {
            // Initialize Guzzle and the retry middleware, include the default options

            $guzzle = new Client(array_merge(
                $this->defaultOptions,
                [
                    'timeout' => $this->timeout,
                    'connect_timeout' => $this->connectTimeout,
                    'http_errors' => false,
                    'verify' => false,
                    'handler' => $this->getStack(),
                    'debug' => false
                ]
            ));

            $this->client = $guzzle;
        }

        return $this->client;
    }

    /**
     * @return GuzzleClient|static
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set Guzzle option
     *
     * @param string $name
     * @param mixed $value
     *
     * @return GuzzleClient
     */
    public function setOption($name, $value)
    {
        // Set the default option
        $this->defaultOptions[$name] = $value;
        // Reset the non-mutable Guzzle client
        $this->client = null;

        return $this;
    }

    /**
     * Get Guzzle option
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getOption($name)
    {
        if (isset($this->defaultOptions[$name])) {
            return $this->defaultOptions[$name];
        }

        return null;
    }

    /**
     * Set the verify setting
     *
     * @param bool|string $verify
     *
     * @return $this
     */
    public function setVerify($verify)
    {
        // Set the verify option
        $this->defaultOptions['verify'] = $verify;
        // Reset the non-mutable Guzzle client
        $this->client = null;

        return $this;
    }

    /**
     * Return verify setting
     *
     * @return bool|string
     */
    public function getVerify()
    {
        if (isset($this->defaultOptions['verify'])) {
            return $this->defaultOptions['verify'];
        }

        return false;
    }

    /**
     * Set the amount of retries
     *
     * @param int $maxRetries
     *
     * @return $this
     */
    public function setMaxRetries($maxRetries)
    {
        $this->maxRetries = $maxRetries;

        return $this;
    }

    /**
     * Return max retries
     *
     * @return int
     */
    public function getMaxRetries()
    {
        return $this->maxRetries;
    }

    /**
     * Set the concurrency
     *
     * @param int $concurrency
     *
     * @return $this
     */
    public function setConcurrency($concurrency)
    {
        $this->concurrency = $concurrency;

        return $this;
    }

    /**
     * Return concurrency
     *
     * @return int
     */
    public function getConcurrency()
    {
        return $this->concurrency;
    }

    /**
     * Set the logger
     *
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Get the logger
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Adds a request to the list of pending requests
     * Using the ID you can replace a request
     *
     * @param string $id Request ID
     * @param string $request PSR-7 request
     *
     * @return int|string
     */
    public function addOrUpdateRequest($id, $request)
    {
        if (is_null($id)) {
            return array_push($this->pendingRequests, $request);
        }

        $this->pendingRequests[$id] = $request;

        return $id;
    }

    /**
     * Remove a request from the list of pending requests
     *
     * @param string $id
     */
    public function removeRequest($id)
    {
        unset($this->pendingRequests[$id]);
    }

    /**
     * Clear all pending requests
     */
    public function clearRequests()
    {
        $this->pendingRequests = [];
    }


    /**
     * http request
     */
    public function doRequest($request)
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


    /**
     * 增加日子中间件
     */
    public function getStack()
    {
        $stack = HandlerStack::create(\GuzzleHttp\choose_handler());
        $formatter = new MessageFormatter("{uri} {request} - {response} \n ------------------- \n");
        $stack->push(Middleware::log($this->logger, $formatter));
        // $stack->push(Middleware::retry(function (
        //     $retries,
        //     Request $request,
        //     Response $response = null,
        //     RequestException $exception = null
        // ) {
        //     // Limit the number of retries to 5
        //     if ($retries >= 5) {
        //         return false;
        //     }

        //     // Retry connection exceptions
        //     if ($exception instanceof ConnectException) {
        //         return true;
        //     }

        //     if ($response) {
        //         // Retry on server errors
        //         // 如果是HTTP STATUS =500 就会重试， ERP不需要
        //         // if ($response->getStatusCode() >= 500) {
        //         //     return true;
        //         // }
        //     }

        //     return false;
        // }, function ($retries) {
        //     return $retries * 1000;
        // }));

        return $stack;
    }

    public function setUsedPhpBuild($v)
    {
        $this->isUsedPhpBuild = $v;
        return $this;
    }

    public function getUsedPhpBuild()
    {
        return $this->isUsedPhpBuild;
    }

    /**
     * http request
     */
    public function ezRequest($method, $uri, $body = null, $header = null)
    {

        if (!is_array($header)) {
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ];
        }

        if ($method == "GET") {
            if (isset($body['query'])) {
                $body = $body['query'];
            }
            $uri .= '?' . Query::build($body);
            $body = null;
        } else {
            if (isset($body['form_params'])) {
                $body = $body['form_params'];
            }

            if ($this->getUsedPhpBuild()) {
                $body = http_build_query($body, '', '&');
            } else {
                $body = Query::build($body);
            }
        }

        $request = new Request(
            $method,
            $uri,
            $header,
            $body
        );

        return $this->doRequest($request);
    }

    /**
     * 转发原生request
     */
    public function request($method, $uri = '', array $options = [])
    {
        $guzzle = $this->getClient();
        return $guzzle->request($method, $uri, $options);
    }
}
