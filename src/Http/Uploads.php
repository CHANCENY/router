<?php

namespace Simp\Router\Http;

class Uploads
{
    private array $files = [];

    public function __construct()
    {
        $this->files = $_FILES;
    }
}