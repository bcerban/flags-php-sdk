<?php

namespace Flags\Tests\Unit\Connection;

use Flags\Connection\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /** @var Response */
    private $response;

    public function setUp() {
        $this->response = new Response(200, "{\"evaluation\": \"true\"}");
    }

    public function testGetResponseBody()
    {
        $this->assertEquals("{\"evaluation\": \"true\"}", $this->response->getResponseBody());
    }

    public function testSetResponseBody()
    {
        $this->response->setResponseBody("{\"evaluation\": \"false\"}");
        $this->assertEquals("{\"evaluation\": \"false\"}", $this->response->getResponseBody());
    }

    public function testGetStatusCode()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testSetStatusCode()
    {
        $this->response->setStatusCode(404);
        $this->assertEquals(404, $this->response->getStatusCode());
    }
}
