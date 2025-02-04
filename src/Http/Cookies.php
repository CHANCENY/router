<?php

namespace Simp\Router\Http;

class Cookies
{
    protected array $cookies = [];

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    public function set($name, $value, $time, $location): void
    {
        $this->cookies[$name] = $value;
        setcookie($name, $value, $time, $location);
    }
    public function get($name)
    {
        return $this->cookies[$name] ?? null;
    }
    public function remove($name): void
    {
        unset($this->cookies[$name]);
    }

    public function all()
    {
        return $this->cookies;
    }
}