<?php

namespace Flags;


use Flags\Connection\Client;
use Flags\Connection\EvaluationRequest;
use Flags\Connection\Response;
use Flags\Exception\ConnectionException;
use Flags\Exception\EvaluationException;

class Evaluator
{
    const STATUS_EVALUATED = 200;
    const KEY_EVALUATION   = 'evaluation';
    const KEY_ERROR        = 'error';
    const KEY_MESSAGE      = 'message';

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
     * @param User $user
     * @param string $applicationUser
     * @return Evaluation
     * @throws ConnectionException
     * @throws EvaluationException
     */
    public function evaluate(Flag $flag, User $user, $applicationUser = '')
    {
        $this->flag = $flag;

        $request = new EvaluationRequest($flag, $user, $applicationUser);
        $response = $this->client->process($request);

        if ($response->getStatusCode() == self::STATUS_EVALUATED) {
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
        $body = $response->getResponseBodyAsArray();

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
        $body = $response->getResponseBodyAsArray();

        if (isset($body[self::KEY_ERROR])) {
            $error = $body[self::KEY_ERROR];
        } else if (isset($body[self::KEY_MESSAGE])) {
            $error = $body[self::KEY_MESSAGE];
        } else {
            $error = sprintf("Message missing from response body: %s", $response->getResponseBody());
        }

        return $error;
    }
}