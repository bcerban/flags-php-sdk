<?php

namespace Flags\Connection;

use Flags\Flag;

class EvaluationRequest implements RequestInterface
{
    private const ENDPOINT_EVALUATE = 'flags/evaluate';

    /** @var Flag */
    private $flag;

    /** @var string */
    private $user;

    /** @var string[] */
    private $config = [];

    /** @var string[] */
    private $headers = [];

    /**
     * EvaluatorRequest constructor.
     * @param Flag $flag
     * @param string $user
     */
    public function __construct(Flag $flag, $user = '')
    {
        $this->flag = $flag;
        $this->user = $user;
    }

    /**
     * @return string[]
     */
    public function getConfig()
    {
        return $this->config;
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
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->user
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