<?php

namespace Flags\Connection;


use Flags\Exception\ConnectionException;

class Response
{
    /** @var string */
    private $statusCode;

    /** @var string */
    private $responseBody;

    /**
     * Response constructor.
     * @param string $statusCode
     * @param string $body
     */
    public function __construct($statusCode, $body)
    {
        $this->statusCode = $statusCode;
        $this->responseBody = $body;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode(string $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * @param string $responseBody
     */
    public function setResponseBody(string $responseBody): void
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @return array
     * @throws ConnectionException
     */
    public function getResponseBodyAsArray(): array
    {
        $body = json_decode($this->responseBody, true);

        if (!$body || !is_array($body)) {
            throw new ConnectionException(sprintf("Response body can't be parsed: %s", $responseBody));
        }

        return $body;
    }
}