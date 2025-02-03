<?php

class Example extends \Simp\Router\Router\RouteEntryController
{

    public function entry(...$args): mixed
    {
        if($args['route_name'] === 'index') {
            return new \Simp\Router\Http\Response("<h1>Home page</h1>");
        }

        else if($args['route_name'] === 'post') {
            /**@var Post $post**/
            $post = $args['request']->query->get('post');
            return new \Simp\Router\Http\Response("<h1>Post: {$post->title}</h1> <p>{$post->content}</p>");
        }
        return new \Simp\Router\Http\Response("<h1>Not Home page</h1>");
    }
}