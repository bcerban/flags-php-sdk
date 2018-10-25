<?php

namespace Flags;


use Flags\Connection\Client;
use Flags\Connection\EvaluationRequest;
use Flags\Connection\Response;
use Flags\Exception\ConnectionException;
use Flags\Exception\EvaluationException;

class Evaluator
{
    const CODE_OK = 200;
    const KEY_EVALUATION = 'evaluation';
    const KEY_ERROR      = 'error';
    const KEY_MESSAGE    = 'message';

    /** @var Client  */
    private $client;

    /** @var Flag */
    private $flag;

    /**
     * Evaluator constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param Flag $flag
     * @param string $user
     * @return Evaluation
     * @throws EvaluationException
     * @throws ConnectionException
     */
    public function evaluate(Flag $flag, $user = '')
    {
        $this->flag = $flag;

        $request = new EvaluationRequest($flag, $user);
        $response = $this->client->process($request);

        if ($response->getStatusCode() == self::CODE_OK) {
            return $this->processEvaluationResponse($response);
        }

        throw new EvaluationException($this->processErrorResponse($response));
    }

    /**
     * @param Response $response
     * @return Evaluation
     * @throws ConnectionException
     */
    private function processEvaluationResponse(Response $response)
    {
        $body = $this->parseResponseBody($response->getResponseBody());

        if (!isset($body[self::KEY_EVALUATION])) {
            throw new ConnectionException(sprintf("Evaluation missing from response body: %s", $response->getResponseBody()));
        }

        return new Evaluation($this->flag, $body[self::KEY_EVALUATION]);
    }

    /**
     * @param Response $response
     * @return string
     * @throws ConnectionException
     */
    private function processErrorResponse(Response $response)
    {
        $error = '';
        $body = $this->parseResponseBody($response->getResponseBody());

        if (isset($body[self::KEY_ERROR])) {
            $error = $body[self::KEY_ERROR];
        } else if (isset($body[self::KEY_MESSAGE])) {
            $error = $body[self::KEY_MESSAGE];
        } else {
            throw new ConnectionException(sprintf("Message missing from response body: %s", $response->getResponseBody()));
        }

        return $error;
    }

    /**
     * @param string $responseBody
     * @return mixed
     * @throws ConnectionException
     */
    private function parseResponseBody(string $responseBody)
    {
        $body = json_decode($responseBody, true);

        if (!$body || !is_array($body)) {
            throw new ConnectionException(sprintf("Response body can't be parsed: %s", $responseBody));
        }

        return $body;
    }
}