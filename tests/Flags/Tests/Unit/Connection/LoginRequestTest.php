<?php

namespace Flags\Tests\Unit\Connection;

use Flags\Connection\LoginRequest;
use Flags\User;
use PHPUnit\Framework\TestCase;

class LoginRequestTest extends TestCase
{
    /** @var User */
    private $user;

    /** @var LoginRequest */
    private $request;

    public function setUp() {
        $this->user = new User('test@email.com', 'testpassword');
        $this->request = new LoginRequest($this->user);
    }

    public function testGetUri()
    {
        $this->assertEquals('auth/login', $this->request->getUri());
    }

    public function testGetHeaders()
    {
        $this->assertEquals([], $this->request->getHeaders());
    }

    public function testGetBody()
    {
        $body = [
            'email'    => $this->user->getEmail(),
            'password' => $this->user->getPassword()
        ];

        $this->assertEquals($body, $this->request->getBody());
    }

    public function testGetConfig()
    {
        $this->assertEquals([], $this->request->getConfig());
    }

    public function testGetMethod()
    {
        $this->assertEquals('POST', $this->request->getMethod());
    }

    public function testGetBodyAsString()
    {
        $body = [
            'email'    => $this->user->getEmail(),
            'password' => $this->user->getPassword()
        ];

        $this->assertEquals(json_encode($body), $this->request->getBodyAsString());
    }
}
