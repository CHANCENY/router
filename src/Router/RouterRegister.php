<?php

namespace Simp\Router\Router;

use GuzzleHttp\Psr7\HttpFactory;
use Simp\Router\middleware\access\Access;
use Simp\Router\middleware\interface\Middleware;
use Simp\Router\middleware\MiddlewareStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class RouterRegister implements RouteInterface
{
    protected Request $request;
    protected mixed $controllerResponse;

    protected MiddlewareStack $access_middleware_stack;

    public function __construct(string|array|null $middleware_register = null)
    {
        $this->request = Request::createFromGlobals();
        $this->access_middleware_stack = new MiddlewareStack;

        if (is_string($middleware_register)) {
            // Case 1: YAML file path
            if (file_exists($middleware_register) && str_ends_with($middleware_register, '.yml')) {
                $middlewares = Yaml::parseFile($middleware_register);

                if (!empty($middlewares['access']) && is_array($middlewares['access'])) {
                    foreach ($middlewares['access'] as $middle) {
                        if (class_exists($middle)) {
                            $instance = new $middle();
                            if ($instance instanceof Middleware) {
                                $this->access_middleware_stack->add($instance);
                            }
                        }
                    }
                }
            }
        } elseif (is_array($middleware_register)) {
            // Case 2: Directly passed array of middleware classes
            foreach ($middleware_register as $middle) {
                if (class_exists($middle)) {
                    $instance = new $middle();
                    if ($instance instanceof Middleware) {
                        $this->access_middleware_stack->add($instance);
                    }
                }
            }
        }
    }
    
    /**
     * @throws NotFoundException
     */
    protected function handlerController($route_name, $controller, $options)
    {
        $request = Request::createFromGlobals();

        $controller_method = $options['controller_method'] ?? $route_name;
        if (!is_callable([$controller, $controller_method])) {
            throw new NotFoundException("controller method '$controller_method' not defined");
        }

        // Handle pre_controller_middleware
        $access = new Access;
        $access->options['controller'] = $controller;
        $access->options['method'] = $controller_method;
        $access->options['options'] = $options;

        $result = $this->access_middleware_stack->handle($request, $access);

        $access = $result['access'] ?? null;
        if($access instanceof Access) {
            if (!$access->access_granted) {
                return $access->response ?? $access->redirect ?? null;
            }
        }

        $GLOBALS['_SERVER']['ROUTE_ATTRIBUTES'] = $options;

        return $controller->$controller_method(request: $request, route_name: $route_name, options: $options);
    }

    /**
     * @throws NotFoundException
     */
    public function get(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'GET') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function post(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'POST') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function put(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'PUT') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'DELETE') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function options(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'OPTIONS') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
            }
        }
    }

    /**
     * @throws NotFoundException
     */
    public function patch(string $path, string $route_name, object $controller, array $options = [])
    {
        if ($this->request->getMethod() == 'PATCH') {
            if ($this->routeMath($path)) {
                $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
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

                        if (($type === 'int' || $type === 'float' || $type === 'double') && is_numeric($current_list[$i])) {
                            $checked_flags[] = true;
                        }
                        elseif ($type === 'bool' && is_bool($params[$key])) {
                            $checked_flags[] = true;
                        }
                        elseif (class_exists($type) && $params[$key] instanceof $type) {
                            $checked_flags[] = true;
                        }
                        elseif ($type === 'string' && is_string($current_list[$i])) {
                            $checked_flags[] = true;
                        }
                    }catch (\Throwable $exception){
                        continue;
                    }

                }

            }

            $checked_flags = array_filter($checked_flags);
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
            $this->controllerResponse = $this->handlerController($route_name,$controller,$options);
        }
    }

    public function send()
    {
        $response = $this->controllerResponse;

        // Case 1: Response is a plain string
        if (is_string($response)) {
            echo $response;
            return $response;
        }

        // Case 2: Response is an object (Symfony or PSR-7)
        if (is_object($response)) {

            // ✅ Symfony Response
            if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
                $response->send();
                return $response;
            }

            // ✅ PSR-7 Response (Guzzle, Nyholm, Laminas, etc.)
            if ($response instanceof \Psr\Http\Message\ResponseInterface) {
                // Send status line
                header(sprintf(
                    'HTTP/%s %d %s',
                    $response->getProtocolVersion(),
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ));

                // Send headers
                foreach ($response->getHeaders() as $name => $values) {
                    foreach ($values as $value) {
                        header("$name: $value", false);
                    }
                }

                // Send body
                $body = $response->getBody();
                if ($body->isSeekable()) {
                    $body->rewind();
                }

                while (!$body->eof()) {
                    echo $body->read(8192);
                }

                return $response;
            }

            // Unsupported object type
            throw new \InvalidArgumentException(sprintf(
                'Unsupported response object of type %s. Expected string, Symfony Response, or PSR-7 ResponseInterface.',
                get_class($response)
            ));
        }

        // Case 3: Invalid type
        throw new \InvalidArgumentException('Controller response must be a string or a supported response object.');
    }

}
