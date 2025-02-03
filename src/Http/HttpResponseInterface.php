<?php

namespace Simp\Router\Http;

interface HttpResponseInterface
{
    public function __construct(mixed $content, int $status = 200, array $headers = []);

    public function send(): void;
}