<?php

namespace Flags;

use Cache\Adapter\Common\CacheItem;
use Flags\Connection\Client;
use Flags\Connection\EvaluationRequest;
use Flags\Connection\Response;
use Flags\Exception\ConnectionException;
use Flags\Exception\ConnectionTimeoutException;
use Flags\Exception\EvaluationException;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;

class Evaluator
{
    const CACHE_DIR = __DIR__;

    const STATUS_EVALUATED = 200;
    const KEY_EVALUATION   = 'evaluation';
    const KEY_ERROR        = 'error';
    const KEY_MESSAGE      = 'message';

    /** @var Client  */
    private $client;

    /** @var Flag */
    private $flag;

    /** @var FilesystemCachePool */
    private $cachePool;

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
        $response = $this->getValueFromCache($request);

        if ($response === null) {
            $response = $this->evaluateRequest($request);
        }

        return $response;
    }

    /**
     * @param EvaluationRequest $request
     * @return Evaluation|null
     */
    private function getValueFromCache(EvaluationRequest $request)
    {
        $response = null;
        $key = $this->getKeyForRequest($request);

        try {
            if ($this->getCachePool()->has($key)) {
                $cachedResponse = $this->getCachePool()->get($key);
                if ($cachedResponse !== null) {
                    $response = new Evaluation($this->flag, $cachedResponse);
                }
            }
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) { }

        return $response;
    }

    /**
     * @param EvaluationRequest $request
     * @return Evaluation
     * @throws ConnectionException
     * @throws EvaluationException
     */
    private function evaluateRequest(EvaluationRequest $request)
    {
        try {
            $response = $this->client->process($request);

            if ($response->getStatusCode() == self::STATUS_EVALUATED) {
                return $this->processEvaluationResponse($request, $response);
            }
        } catch(ConnectionTimeoutException $e) {
            return new Evaluation($this->flag, false);
        }

        throw new EvaluationException($this->processErrorResponse($response));
    }
    /**
     * @param EvaluationRequest $request
     * @param Response $response
     * @return Evaluation
     * @throws ConnectionException
     */
    private function processEvaluationResponse(EvaluationRequest $request, Response $response)
    {
        $body = $response->getResponseBodyAsArray();

        if (!isset($body[self::KEY_EVALUATION])) {
            throw new ConnectionException(sprintf("Evaluation missing from response body: %s", $response->getResponseBody()));
        }

        $value = $body[self::KEY_EVALUATION];
        $this->cacheResponse($request, $value);

        return new Evaluation($this->flag, $value);
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

    /**
     * @param EvaluationRequest $request
     * @param $result
     */
    private function cacheResponse(EvaluationRequest $request, $result)
    {
        $cacheItem = new CacheItem($this->getKeyForRequest($request), true, $result);
        $cacheItem->expiresAfter(120);
        $this->getCachePool()->save($cacheItem);
    }

    /**
     * @return FilesystemCachePool
     */
    private function getCachePool()
    {
        if ($this->cachePool === null) {
            $filesystemAdapter = new Local(self::CACHE_DIR);
            $filesystem        = new Filesystem($filesystemAdapter);
            $this->cachePool = new FilesystemCachePool($filesystem);
        }

        return $this->cachePool;
    }

    /**
     * @param EvaluationRequest $request
     * @return string
     */
    private function getKeyForRequest(EvaluationRequest $request)
    {
        $params     = $request->getBody();
        $flagToken  = $params['flag_identifier'] ?? '';
        $userId     = $params['user_identifier'] ?? 'no_user';

        return sprintf("%s_%s", $flagToken, $userId);
    }
}