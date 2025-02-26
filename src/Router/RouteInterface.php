<?php

namespace Simp\Router\Router;

interface RouteInterface
{
    /**
     * Set up GET route path
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function get(string $path, string $route_name, object $controller, array $options = []);

    /**
     * Set Up POST.
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function post(string $path, string $route_name, object $controller, array $options = []);

    /**
     * Set Up PUT route
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function put(string $path, string $route_name, object $controller, array $options = []);

    /**
     * Set up DELETE route.
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function delete(string $path, string $route_name, object $controller, array $options = []);

    /**
     * Set up OPTIONS route.
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function options(string $path, string $route_name, object $controller, array $options = []);

    /**
     * Set up PATCH route
     * @param string $path
     * @param string $route_name
     * @param object $controller
     * @param array $options
     * @return mixed
     */
    public function patch(string $path, string $route_name, object $controller, array $options = []);
}