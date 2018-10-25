<?php

namespace Flags\Tests\Unit\Connection;

use Flags\Connection\EvaluationRequest;
use Flags\Flag;
use Flags\User;
use PHPUnit\Framework\TestCase;

class EvaluationRequestTest extends TestCase
{
    /** @var Flag */
    private $flag;

    /** @var User */
    private $user;

    /** @var string */
    private $applicationUser;

    /** @var EvaluationRequest */
    private $request;

    public function setUp() {
        $this->flag = new Flag('someflagidentifier');
        $this->user = new User();
        $this->user->setToken('authtoken');
        $this->applicationUser = 'someuseridentifier';

        $this->request = new EvaluationRequest($this->flag, $this->user, $this->applicationUser);
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
        $this->assertEquals(
            ['Authorization: authtoken'],
            $this->request->getHeaders()
        );
    }

    public function testGetBody()
    {
        $body = [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->applicationUser
        ];
        $this->assertEquals($body, $this->request->getBody());
    }

    public function testGetBodyAsString()
    {
        $body = [
            'flag_identifier' => $this->flag->getIdentifier(),
            'user_identifier' => $this->applicationUser
        ];
        $this->assertEquals(json_encode($body), $this->request->getBodyAsString());
    }

    public function testGetConfig()
    {
        $this->assertEquals([], $this->request->getConfig());
    }
}
