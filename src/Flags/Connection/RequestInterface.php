<?php

namespace Flags\Connection;


interface RequestInterface
{
    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    /**
     * @return string[]
     */
    public function getConfig();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string[]
     */
    public function getHeaders();

    /**
     * @return array
     */
    public function getBody();

    /**
     * @return string
     */
    public function getBodyAsString();
}