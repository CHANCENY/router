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
use Simp\Router\Route;
require_once "Example.php";

Route::get("/", "index", Example::class);
Route::get("/api/posts", "posts", Example::class);
Route::get("/api/post/[id:int]", "post", Example::class);
Route::post("/api/post", "post_create", Example::class);
Route::put("/api/post/[id:int]", "post_update", Example::class);
Route::delete("/api/post/[id:int]", "post_delete", Example::class);
Route::get("/api/posts/search/[title]", "post_search", Example::class);
Route::get("/api/post/[id:int]/image","post_image",Example::class);
```

### Handling Requests
Route handlers should extend `RouteEntryController` and implement an `entry` method to handle requests. Example:

```php
use Simp\Router\Http\Response;

class Example extends \Simp\Router\Router\RouteEntryController
{
    private array $posts;

    public function __construct()
    {
        $this->posts = json_decode(file_get_contents(__DIR__ . '/posts.json'), true);
    }

    public function entry(...$args): mixed
    {
        if($args['route_name'] === 'posts') {
            return new Response($this->posts, 200, ['Content-Type' => 'application/json']);
        }
    }
}
```

## API Endpoints

| Method | Endpoint                      | Description                     |
|--------|--------------------------------|---------------------------------|
| GET    | `/api/posts`                  | Get all posts                   |
| GET    | `/api/post/[id:int]`          | Get a specific post by ID       |
| POST   | `/api/post`                   | Create a new post               |
| PUT    | `/api/post/[id:int]`          | Update an existing post         |
| DELETE | `/api/post/[id:int]`          | Delete a post                   |
| GET    | `/api/posts/search/[title]`   | Search posts by title           |
| GET    |  `/api/post/[id:int]/image`   | Get post image base64 data      |

## Requirements
- PHP 8.0+
- Composer

## License
This project is licensed under the MIT License.

