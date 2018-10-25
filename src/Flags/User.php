<?php

namespace Flags;


class User
{
    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var string */
    private $token;

    /**
     * User constructor.
     * @param string $email
     * @param string $pass
     */
    public function __construct($email = '', $pass = '')
    {
        $this->email    = $email;
        $this->password = $pass;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}