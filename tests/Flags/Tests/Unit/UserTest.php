<?php

namespace Flags\Tests\Unit;

use Flags\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    private $user;

    public function setUp() {
        $this->user = new User('test@email.com', 'testpassword');
    }

    public function testSetEmail()
    {
        $this->user->setEmail('test2@email.com');
        $this->assertEquals('test2@email.com', $this->user->getEmail());
    }

    public function testSetPassword()
    {
        $this->user->setPassword('testpassword2');
        $this->assertEquals('testpassword2', $this->user->getPassword());
    }

    public function testGetEmail()
    {
        $this->assertEquals('test@email.com', $this->user->getEmail());
    }

    public function testGetPassword()
    {
        $this->assertEquals('testpassword', $this->user->getPassword());
    }
}
