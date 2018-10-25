<?php

namespace Flags\Tests\Unit;

use Flags\Flag;

class FlagTest extends \PHPUnit\Framework\TestCase
{
    /** @var Flag */
    private $flag;

    public function setUp() {
        $this->flag = new Flag('flag_id', 'flag_name');
    }

    public function testSetIdentifier()
    {
        $this->flag->setIdentifier('new_id');
        $this->assertEquals('new_id', $this->flag->getIdentifier());
    }

    public function testGetIdentifier()
    {
        $this->assertEquals('flag_id', $this->flag->getIdentifier());
    }

    public function testSetName()
    {
        $this->flag->setName('new_name');
        $this->assertEquals('new_name', $this->flag->getName());
    }

    public function testGetName()
    {
        $this->assertEquals('flag_name', $this->flag->getName());
    }
}
