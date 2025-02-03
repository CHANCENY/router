<?php

namespace Simp\Router\Http;

class Request
{
    public Headers $headers;
    public Cookies $cookies;
    public Query $query;

    public Uploads $files;

    public Server $server;
    public PayLoad $payload;

    public function __construct()
    {
        $this->headers = new Headers();
        $this->cookies = new Cookies();
        $this->query = new Query();
        $this->files = new Uploads();
        $this->server = new Server();
        $this->payload = new PayLoad();
    }

}