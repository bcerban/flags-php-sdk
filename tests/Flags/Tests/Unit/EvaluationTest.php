<?php

namespace Flags\Tests\Unit;

use Flags\Flag;
use Flags\Evaluation;
use PHPUnit\Framework\TestCase;

class EvaluationTest extends TestCase
{
    /** @var Flag */
    private $flag;

    /** @var Evaluation */
    private $evaluation;

    public function setUp() {
        $this->flag = new Flag('test_flag');
        $this->evaluation = new Evaluation($this->flag, true);
    }

    public function testGetResult()
    {
        $this->assertTrue($this->evaluation->getResult());
    }

    public function testGetFlag()
    {
        $this->assertEquals($this->flag, $this->evaluation->getFlag());
    }

    public function testSetResult()
    {
        $this->evaluation->setResult(false);
        $this->assertFalse($this->evaluation->getResult());
    }

    public function testSetFlag()
    {
        $flag = new Flag('other');
        $this->evaluation->setFlag($flag);
        $this->assertEquals($flag, $this->evaluation->getFlag());
    }
}
