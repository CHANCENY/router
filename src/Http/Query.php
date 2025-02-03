<?php

namespace Simp\Router\Http;

class Query
{
    private array $query = [];

    public function __construct()
    {
        $this->query = $_GET;
    }
    public function get(string $key, $default = null) {
        return $this->query[$key] ?? $default;
    }
    public function set(string $key, $value): void {
        $this->query[$key] = $value;
    }
    public function has(string $key): bool {
        return array_key_exists($key, $this->query);
    }

    public function all(): array {
        return $this->query;
    }
}