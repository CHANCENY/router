<?php


namespace Simp\Router\middleware\interface;

use Simp\Router\middleware\access\Access;
use Symfony\Component\HttpFoundation\Request;


interface Middleware {

    /**
     * This function should return $next() with $request passed as argument.
     * Note: router will look for 
     */
    public function __invoke(Request $request, Access $access_interface, $next);
}