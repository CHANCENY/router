<?php

namespace Simp\Router;

use ReflectionException;
use Simp\Router\Router\NotFoundException;
use Simp\Router\Router\RouterRegister;


class Route
{
    protected RouterRegister $router_register;

    public function __construct(string|array|null $middleware_register_file = null)
    {
        $this->router_register = new RouterRegister($middleware_register_file);
    }
    /**
     * @param string $controller_class
     * @return object
     * @throws ReflectionException
     */
    private static function entry(string $controller_class): object
    {
        $list = explode("@", $controller_class);
        $reflection = new \ReflectionClass($list[0]);
        return $reflection->newInstance();
    }

    /**
     * Listener for HTTP method GET
     * @param string $path
     * @param string $route_name
     * @param string|object $controller
     * @param array $options
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function get(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->get($path, $route_name, self::entry($controller), $options);
    }

    /**
     * Listener for HTTP method POST
     * @param string $path The URI path to associate with the POST method.
     * @param string $route_name A name for the route being registered.
     * @param string|object $controller The associated controller or callable handling the request.
     * @param array $options Additional options or parameters for route configuration.
     * @throws ReflectionException If reflection-related errors occur during execution.
     * @throws NotFoundException If the route or corresponding controller is not found.
     */
    public function post(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->post($path, $route_name, self::entry($controller), $options);
    }

    /**
     * Listener for HTTP method PUT
     * @param string $path
     * @param string $route_name
     * @param string|object $controller
     * @param array $options
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function put(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->put($path, $route_name, self::entry($controller), $options);
    }

    /**
     * Listener for HTTP method DELETE
     * @param string $path The URI path to map the DELETE request to.
     * @param string $route_name The name of the route to associate with the DELETE request.
     * @param string|object $controller The controller handling the request, either as a class/method string or an object.
     * @param array $options Additional options for route handling.
     * @throws ReflectionException If there is an error reflecting on the controller.
     * @throws NotFoundException If the specified route or controller is not found.
     */
    public function delete(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->delete($path, $route_name, self::entry($controller), $options);
    }

    /**
     * Listener for HTTP method OPTIONS
     * @param string $path
     * @param string $route_name
     * @param string|object $controller
     * @param array $options
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function options(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->options($path, $route_name, self::entry($controller), $options);
    }

    public function patch(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->patch($path, $route_name, self::entry($controller), $options);
    }

    /**
     * Listener for any HTTP method
     * @param string $path
     * @param string $route_name
     * @param string|object $controller
     * @param array $options
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function any(string $path, string $route_name, $controller, array $options = []) {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        $this->router_register->any($path, $route_name, self::entry($controller), $options);
    }

    public function send(string $psr7ResponseClass, int $override_status_code = 200, array $override_headers = []) {
        $this->router_register->send($psr7ResponseClass, $override_status_code, $override_headers);
    }
}