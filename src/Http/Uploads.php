<?php

namespace Simp\Router\Http;

class Uploads
{
    private array $files = [];

    public function __construct()
    {
        $this->files = $_FILES;
    }

    public function get(string $name)
    {
        if (array_key_exists($name, $this->files)) {
            return $this->files[$name];
        }
        return null;
    }

    public function all(): array
    {
        return $this->files;
    }
}