<?php

namespace Simp\Router\Http;

class Response implements HttpResponseInterface
{

    private $content;
    private $statusCode;
    private $headers;

    public function __construct(mixed $content, int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        http_response_code($this->statusCode);
        echo $this->content;
        exit;
    }
}