<?php

namespace Simp\Router\Http;

class Server
{
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }
    public function getUri()
    {
        return $this->server['REQUEST_URI'];
    }
    public function getHost() {
        return $this->server['HTTP_HOST'];
    }
    public function getPort() {
        return $this->server['SERVER_PORT'];
    }
    public function getScheme() {
        return $this->server['REQUEST_SCHEME'];
    }
    public function getProtocolVersion() {
        return $this->server['SERVER_PROTOCOL'];
    }
    public function getHeaders() {
        return $this->server;
    }
    public function getHeader($header) {
        return $this->server[$header] ?? null;
    }

}