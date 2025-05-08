<?php

namespace Simp\Router;

use ReflectionException;
use Simp\Router\Router\RouterRegister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Route
{
    protected RouterRegister $router_register;

    public function __construct(?string $middleware_register_file = null)
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

    public function get(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->get($path, $route_name, self::entry($controller), $options);
    }

    public function post(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->post($path, $route_name, self::entry($controller), $options);
    }

    public function put(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->put($path, $route_name, self::entry($controller), $options);
    }

    public function delete(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->delete($path, $route_name, self::entry($controller), $options);
    }

    public function options(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->options($path, $route_name, self::entry($controller), $options);
    }

    public function patch(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->patch($path, $route_name, self::entry($controller), $options);
    }

    public function any(string $path, string $route_name, $controller, array $options = []): Response|JsonResponse|RedirectResponse|null {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);
        return $this->router_register->any($path, $route_name, self::entry($controller), $options);
    }
}