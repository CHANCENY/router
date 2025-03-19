<?php



require_once "vendor/autoload.php";

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



