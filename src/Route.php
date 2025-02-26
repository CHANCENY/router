<?php

namespace Simp\Router;

use ReflectionException;
use Simp\Router\Router\RouterRegister;

class Route
{
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

    public static function get(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->get($path, $route_name, self::entry($controller), $options);
    }

    public static function post(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->post($path, $route_name, self::entry($controller), $options);
    }

    public static function put(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->put($path, $route_name, self::entry($controller), $options);
    }

    public static function delete(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->delete($path, $route_name, self::entry($controller), $options);
    }

    public static function options(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->options($path, $route_name, self::entry($controller), $options);
    }

    public static function patch(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->patch($path, $route_name, self::entry($controller), $options);
    }

    public static function any(string $path, string $route_name, $controller, array $options = []): void {
        $list = str_contains($controller, "@") ? explode("@", $controller) : [$controller, $route_name];
        $options['controller_method'] = end($list);

        (new RouterRegister())->any($path, $route_name, self::entry($controller), $options);
    }
}