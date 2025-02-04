<?php

namespace Simp\Router;

use Simp\Router\Router\RouteEntryController;
use Simp\Router\Router\RouterRegister;

class Route
{
    /**
     * @param string $controller_class
     * @return mixed|RouteEntryController
     * @throws \Exception
     */
    private static function entry(string $controller_class) {
        $object = new $controller_class();
        return $object instanceof RouteEntryController ? $object :
            throw new \Exception("$controller_class does not extends ".RouteEntryController::class);
    }

    public static function get(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->get($path, $route_name, self::entry($controller), $options);
    }

    public static function post(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->post($path, $route_name, self::entry($controller), $options);
    }

    public static function put(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->put($path, $route_name, self::entry($controller), $options);
    }

    public static function delete(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->delete($path, $route_name, self::entry($controller), $options);
    }

    public static function options(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->options($path, $route_name, self::entry($controller), $options);
    }

    public static function patch(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->patch($path, $route_name, self::entry($controller), $options);
    }

    public static function any(string $path, string $route_name, $controller, array $options = []): void {
        new RouterRegister()->any($path, $route_name, self::entry($controller), $options);
    }
}