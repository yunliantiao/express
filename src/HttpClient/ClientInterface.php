<?php

namespace TxTech\Express\HttpClient;

use Psr\Log\LoggerInterface;

/**
 * Interface ClientInterface
 *
 * @package HttpClient
 */
interface ClientInterface
{
    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Get the HTTP Client instance
     *
     * @return static
     */
    public static function getInstance();

    /**
     * Adds a request to the list of pending requests
     * Using the ID you can replace a request
     *
     * @param string $id Request ID
     * @param string $request PSR-7 request
     *
     * @return int|string
     */
    public function addOrUpdateRequest($id, $request);

    /**
     * Set the verify setting
     *
     * @param bool|string $verify
     *
     * @return $this
     */
    public function setVerify($verify);

    /**
     * Return verify setting
     *
     * @return bool|string
     */
    public function getVerify();

    /**
     * Remove a request from the list of pending requests
     *
     * @param string $id
     */
    public function removeRequest($id);

    /**
     * Clear all requests
     */
    public function clearRequests();


    public function doRequest($request);
}
