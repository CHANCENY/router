<?php

namespace Simp\Router\Router;

use Simp\Router\Http\Request;

class RouterRegister implements RouteInterface
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'GET') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    public function post(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'POST') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    public function put(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'PUT') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    public function delete(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'DELETE') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    public function options(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'OPTIONS') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    public function patch(string $path, string $route_name, RouteEntryController $controller, array $options = []): void
    {
        if ($this->request->server->getMethod() == 'PATCH') {
            if ($this->routeMath($path)) {

                $request = new Request();

                // Handle pre_controller_middleware
                $pre_middlewares = $options['pre_middlewares'] ?? [];
                foreach ($pre_middlewares as $pre_middleware) {
                    new $pre_middleware($request);
                }

                $controller_response = $controller->entry(request: $request, route_name: $route_name);

                $post_middlewares = $options['post_middlewares'] ?? [];
                foreach ($post_middlewares as $post_middleware) {
                    new $post_middleware(request: $request, response: $controller_response);
                }

                $controller_response->send();
            }
        }
    }

    private function routeMath(string $path): bool
    {
        $uri = $this->request->server->getUri();
        $path_uri = parse_url($uri, PHP_URL_PATH);
        // Simple way
        $pattern_list = explode('/', $path);
        $current_list = explode('/', $path_uri);

        // Filter empty element
        $pattern_list = array_values(array_filter($pattern_list));
        $current_list = array_values(array_filter($current_list));

        if (count($pattern_list) == 0 && count($current_list) == 0) {
            return true;
        }

        if(count($pattern_list) === count($current_list)) {

            $params = [];
            $checked_flags = [];

            for ($i = 0; $i < count($pattern_list); $i++) {

                if ( ( !str_starts_with($pattern_list[$i], '[') && !str_ends_with($pattern_list[$i], ']'))
                    && trim($pattern_list[$i]) === trim($current_list[$i])) {
                    $checked_flags[] = true;
                }
                else if ( str_starts_with($pattern_list[$i], '[') && str_ends_with($pattern_list[$i], ']') ) {

                    $param = str_replace(["[", "]"], "", $pattern_list[$i]);
                    $list = explode(':', trim($param));
                    $type = trim(end($list), ']');
                    $key = trim($list[0], '[');
                    $value = urldecode($current_list[$i]);
                    try{
                        // In case the params is of class.
                        if(class_exists($type)) {
                            $params[$key] = new ($type)($value);
                        }
                        else {

                            // Primitive data type conversions.
                            $function = $type."val";
                            $params[$key] =  function_exists($function) ? $function($value) : $value;
                        }

                        $checked_flags[] = true;
                    }catch (\Throwable $exception){
                        continue;
                    }

                }

            }

            if (count($checked_flags) == count($pattern_list)) {
                $_GET = array_merge($_GET, $params);
                return true;
            }
        }
        return false;
    }

    public function any(string $path, string $route_name, RouteEntryController $controller, array $options)
    {
        if ($this->routeMath($path)) {

            $request = new Request();

            // Handle pre_controller_middleware
            $pre_middlewares = $options['pre_middlewares'] ?? [];
            foreach ($pre_middlewares as $pre_middleware) {
                new $pre_middleware($request);
            }

            $controller_response = $controller->entry(request: $request, route_name: $route_name);

            $post_middlewares = $options['post_middlewares'] ?? [];
            foreach ($post_middlewares as $post_middleware) {
                new $post_middleware(request: $request, response: $controller_response);
            }

            $controller_response->send();
        }
    }

}