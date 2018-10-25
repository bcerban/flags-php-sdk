<?php

namespace Flags;


use Flags\Connection\Client;
use Flags\Connection\LoginRequest;
use Flags\Connection\Response;
use Flags\Exception\AuthException;

class Authorizer
{
    const STATUS_AUTHORIZED = 200;
    const KEY_AUTH_TOKEN    = 'auth_token';

    /** @var Client  */
    private $client;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param User $user
     * @return User
     * @throws AuthException
     * @throws Exception\ConnectionException
     */
    public function authorize(User $user)
    {
        $this->user = $user;

        $request = new LoginRequest($user);
        $response = $this->client->process($request);

        if ($response->getStatusCode() == self::STATUS_AUTHORIZED) {
            return $this->processAuthResponse($response);
        }

        throw new AuthException(sprintf("Login request failed: %s", $response->getResponseBody()));
    }

    /**
     * @param Response $response
     * @return User
     * @throws AuthException
     * @throws Exception\ConnectionException
     */
    private function processAuthResponse(Response $response)
    {
        $body = $response->getResponseBodyAsArray();

        if (isset($body[self::KEY_AUTH_TOKEN])) {
            $this->user->setToken($body[self::KEY_AUTH_TOKEN]);
        } else {
            throw new AuthException(sprintf("Auth token is missing: %s", $response->getResponseBody()));
        }
        return $this->user;
    }
}