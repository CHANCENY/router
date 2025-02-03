<?php

namespace Simp\Router\Http;

class Cookies
{
    protected $cookies = [];

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }
}