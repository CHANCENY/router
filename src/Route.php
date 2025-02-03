<?php

namespace Simp\Router;

use Simp\Router\Router\RouteEntryController;

class Route
{
    public static function entry(string $controller_class)
    {
        $object = new $controller_class();
        return $object instanceof RouteEntryController ? $object :
            throw new \Exception("$controller_class does not extends ".RouteEntryController::class);
    }
}