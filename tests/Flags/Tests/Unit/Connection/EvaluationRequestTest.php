<?php

namespace Flags\Tests\Unit\Connection;

use Flags\Connection\EvaluationRequest;
use Flags\Flag;
use PHPUnit\Framework\TestCase;

class EvaluationRequestTest extends TestCase
{
    /** @var Flag */
    private $flag;

    /** @var string */
    private $user;

    /** @var EvaluationRequest */
    private $request;

    public function setUp() {
        $this->flag = new Flag('someflagidentifier');
        $this->user = 'someuseridentifier';

        $this->request = new EvaluationRequest($this->flag, $this->user);
    }

    public function testGetUri()
    {
        $this->assertEquals('flags/evaluate', $this->request->getUri());
    }

    public function testGetMethod()
    {
        $this->assertEquals('POST', $this->request->getMethod());
    }

    public function testGetHeaders()
    {
        $this->assertEquals([], $this->request->getHeaders());
    }

    public function testGetBody()
    {
        $body = [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->user
        ];
        $this->assertEquals($body, $this->request->getBody());
    }

    public function testGetBodyAsString()
    {
        $body = [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->user
        ];
        $this->assertEquals(json_encode($body), $this->request->getBodyAsString());
    }

    public function testGetConfig()
    {
        $this->assertEquals([], $this->request->getConfig());
    }
}
