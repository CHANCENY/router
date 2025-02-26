<?php

namespace Simp\Router\Router;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouterRegister implements RouteInterface
{
    private Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    /**
     * @throws NotFoundException
     */
    protected function handlerController($route_name, $controller, $options): void
    {
        $request = Request::createFromGlobals();

        $controller_method = $options['controller_method'] ?? $route_name;
        if (!is_callable([$controller, $controller_method])) {
            throw new NotFoundException("controller method '$controller_method' not defined");
        }

        // Handle pre_controller_middleware
        $pre_middlewares = $options['pre_middlewares'] ?? [];
        foreach ($pre_middlewares as $pre_middleware) {
            new $pre_middleware($request);
        }

        /**@var Response|RedirectResponse|JsonResponse $controller_response **/
        $controller_response = $controller->$controller_method(request: $request, route_name: $route_name);
        $post_middlewares = $options['post_middlewares'] ?? [];
        foreach ($post_middlewares as $post_middleware) {
            new $post_middleware(request: $request, response: $controller_response);
        }
        $controller_response->send(true);
    }

    /**
     * @throws NotFoundException
     */
    public function get(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'GET') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function post(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'POST') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function put(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'PUT') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'DELETE') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function options(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'OPTIONS') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function patch(string $path, string $route_name, object $controller, array $options = []): void
    {
        if ($this->request->getMethod() == 'PATCH') {
            if ($this->routeMath($path)) {
                $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    private function routeMath(string $path): bool
    {
        $uri = $this->request->getUri();
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

    /**
     * @throws NotFoundException
     */
    public function any(string $path, string $route_name, object $controller, array $options)
    {
        if ($this->routeMath($path)) {
            $this->handlerController($route_name,$controller,$options);
        }
    }

}