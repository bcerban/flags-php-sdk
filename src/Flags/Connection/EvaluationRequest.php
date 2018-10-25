<?php

namespace Flags\Connection;

use Flags\Flag;
use Flags\User;

class EvaluationRequest implements RequestInterface
{
    private const ENDPOINT_EVALUATE = 'flags/evaluate';

    /** @var Flag */
    private $flag;

    /** @var User */
    private $user;

    /** @var string */
    private $applicationUser;

    /**
     * EvaluatorRequest constructor.
     * @param Flag $flag
     * @param string $applicationUser
     */
    public function __construct(
        Flag $flag,
        User $user,
        $applicationUser = ''
    )
    {
        $this->flag = $flag;
        $this->user = $user;
        $this->applicationUser = $applicationUser;
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
        return self::ENDPOINT_EVALUATE;
    }

    public function getHeaders()
    {
        return [
            'Authorization: ' . $this->user->getToken()
        ];
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->applicationUser
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