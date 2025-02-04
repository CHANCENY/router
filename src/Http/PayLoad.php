<?php

namespace Simp\Router\Http;

class PayLoad
{
    private array $payload;

    public function __construct()
    {
        $this->payload = $_POST;
    }
    public function getPayload(): array
    {
        return $this->payload;
    }
    public function get(string $key, $default = null)
    {
        return $this->payload[$key] ?? $default;
    }
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->payload);
    }
    public function set(string $key, $value)
    {
        $this->payload[$key] = $value;
    }
    public function unset(string $key)
    {
        unset($this->payload[$key]);
    }
    public function getContent(): string
    {
        return file_get_contents('php://input');
    }
}