<?php

namespace Flags\Connection;

use Flags\User;

class LoginRequest implements RequestInterface
{
    private const ENDPOINT_LOGIN = 'auth/login';

    /** @var User */
    private $user;

    /**
     * LoginRequest constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string[]
     */
    public function getConfig()
    {
        return [];
    }

    public function getMethod()
    {
        return self::METHOD_POST;
    }

    public function getUri()
    {
        return self::ENDPOINT_LOGIN;
    }

    public function getHeaders()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return [
            'email'    => $this->user->getEmail(),
            'password' => $this->user->getPassword()
        ];
    }

    /**
     * @return string
     */
    public function getBodyAsString()
    {
        return json_encode($this->getBody());
    }
}