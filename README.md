# Router Library

## Overview
This is a simple PHP routing library that allows defining and handling routes easily. It supports various HTTP methods, dynamic parameters, and optional data type specifications.

## Features
- Supports GET, POST, PUT, DELETE requests
- Allows route parameters with type enforcement (int, float, bool, double)
- Handles dynamic routing with class-based controllers
- JSON-based response handling

## Installation
1. Clone the repository or download the source code.
2. Install dependencies using Composer:
   ```sh
   composer require simp/router
   ```

## Usage

### Defining Routes
Routes are defined in `index.php` using the `Route` class. Example:

```php

equire_once "vendor/autoload.php";

use Simp\Router\Route;

require_once "Example.php";
require_once "Help.php";
require_once "ExampleMiddleware.php";


// Make the middleware_register_file where you can declare middlewares.
$middleware_register_file = __DIR__ . '/middleware.yml';

$route = new Route($middleware_register_file);

$route->get("/","index",Example::class);

$route->get("/api/help-document/[help_title:".Help::class."]","api_help",Example::class . "@help");

$route->get("/api/posts","posts",Example::class . "@posts");

$route->get("/api/post/[id:int]","post",Example::class. "@post");

$route->post("/api/post","post_create",Example::class . "@post_create");

$route->delete("/api/post/[id:int]","post_delete",Example::class . "@post_delete");

$route->put("/api/post/[id:int]","post_update",Example::class);

$route->get("/api/posts/search/[title]","post_search",Example::class);

$route->get("/api/post/[id:int]/image","post_image",Example::class);

```

### Handling Requests
Route handlers should extend `RouteEntryController` and implement an `entry` method to handle requests. Example:

```php
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Example
{

    private array $posts;

    public function __construct()
    {
        $this->posts = json_decode(file_get_contents(__DIR__ . '/posts.json'), true);
    }

    function index(...$args): object
    {
        return new Response("<h1>Post Simple Api</h1>");
    }

    function posts(...$args): object
    {
        return new JsonResponse($this->posts,200, ['Content-Type' => 'application/json']);
    }

    function post(...$args): object
    {
        $post = array_filter($this->posts, fn ($post) => $post['id'] === $args['request']->query->get('id'));
        return new JsonResponse(array_values($post),200, ['Content-Type' => 'application/json']);
    }

    function post_create(...$args): object
    {
        $data = json_decode($args['request']->payload->getContent(), true);
        $data['id'] = time();
        $this->posts[] = $data;
        file_put_contents(__DIR__ . '/posts.json', json_encode($this->posts, JSON_PRETTY_PRINT));
        return new JsonResponse($data,200, ['Content-Type' => 'application/json']);
    }

    function post_delete(...$args): object
    {
        $id = $args['request']->query->get('id');
        $this->posts = array_filter($this->posts, fn ($post) => $post['id'] !== $id);
        file_put_contents(__DIR__ . '/posts.json', json_encode($this->posts, JSON_PRETTY_PRINT));
        return new JsonResponse(['status'=>200, 'msg'=>'deleted post'],200, ['Content-Type' => 'application/json']);
    }

    function post_update(...$args): object
    {
        $data = json_decode($args['request']->payload->getContent(), true);
        $id = $args['request']->query->get('id');

        $updated = false;
        $posts = array_map(function ($post) use ($data, $id, &$updated,&$count) {
            if ($post['id'] === $id) {
                foreach ($post as $key => $value) {
                    if (!empty($data[$key])) {
                        $post[$key] = $data[$key];
                        $updated = true;
                    }
                }
            }

            return $post;
        },$this->posts);

        if(!$updated) {
            $post = array_filter($posts, fn ($post) => $post['id'] === $id);
            if ($post) {
                $index = array_keys($post);
                $index = $index[0];
                $new_data = array_merge(reset($post), $data);
                $posts[$index] = $new_data;
                $updated = true;
            }

        }


        $this->posts = array_values($posts);
        file_put_contents(__DIR__ . '/posts.json', json_encode($this->posts, JSON_PRETTY_PRINT));
        return new JsonResponse(['status'=>$updated],200, ['Content-Type' => 'application/json']);
    }

    function post_search(...$args): object
    {
        $matches = [];
        $title = $args['request']->query->get('title');
        foreach ($this->posts as $post) {
            $post_title = $post['title'];
            $percent = 0;

            similar_text(strtolower($post_title), strtolower($title), $percent);
            if ($percent > 50) {
                $matches[] = $post;

            }
        }
        return new JsonResponse($matches,200, ['Content-Type' => 'application/json']);
    }

    function api_help(...$args): object
    {
        return new Response($args['request']->query->get('help_title')->content , 200);
    }

    function post_image(...$args): object
    {
        $id = $args['request']->query->get('id');
        $post_found = array_filter($this->posts, fn ($post) => $post['id'] === $id);
        if ($post = reset($post_found)) {
            $image = $post['image'] ?? null;
            if ($image) {
                $content = file_get_contents($image);
                $base64 = "data:application/octet-stream;base64," . base64_encode($content);
                return new JsonResponse(['image'=>$base64],200, ['Content-Type' => "application/json"]);
            }
        }
        return new Response(null,404, ['Content-Type' => 'application/json']);
    }
}
```

## Requirements
- PHP 8.0+
- Composer

## License
This project is licensed under the MIT License.

