<?php

namespace Simp\Router\Http;

class Headers
{
    private $headers = [];

    public function __construct()
    {
        $this->headers = getallheaders();
    }
}