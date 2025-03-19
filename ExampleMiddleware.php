<?php

use Simp\Router\middleware\access\Access;
use Symfony\Component\HttpFoundation\Request;
use Simp\Router\middleware\interface\Middleware;


class ExampleMiddleware implements Middleware {

    public function __invoke(Request $request, Access $access, $next)
    {
        $access->access_granted = false;
        return $next($request,$access);
    }
}