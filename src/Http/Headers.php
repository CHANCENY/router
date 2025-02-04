<?php

namespace Simp\Router\Http;

class Headers
{
    private array $headers = [];

    public function __construct()
    {
        $this->headers = getallheaders() ?? [];
    }
    public function get(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->headers);
    }
    public function all(): array
    {
        return $this->headers;
    }

}