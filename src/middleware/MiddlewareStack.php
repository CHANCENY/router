<?php

namespace Simp\Router\middleware;

use Simp\Router\middleware\access\Access;
use Symfony\Component\HttpFoundation\Request;
use Simp\Router\middleware\interface\Middleware;

class MiddlewareStack 
{
    protected $start;

    public function __construct()
    {
        $this->start = function(Request $request, Access $access) {
            return ['request'=>$request, 'access'=>$access];
        };
    }

    public function add(Middleware $middleware) {

        $next = $this->start;
        $this->start = function(Request $request, Access $access) use($middleware, $next) {
            return $middleware($request,$access, $next);
        };
    }

    public function handle(Request $request, Access $access)  {
        return call_user_func($this->start, $request, $access);
    }
}