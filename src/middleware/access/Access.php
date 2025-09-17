<?php

namespace Simp\Router\middleware\access;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Access {

    public bool $access_granted = true;

    public RedirectResponse $redirect;

    public Response $response;

    public array $options = [];
    
}