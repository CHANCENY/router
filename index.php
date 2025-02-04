<?php



require_once "vendor/autoload.php";

use Simp\Router\Route;

require_once "Example.php";
require_once "Help.php";

Route::get("/","index",Example::class);

Route::get("/api/help-document/[help_title:".Help::class."]","api_help",Example::class);

Route::get("/api/posts","posts",Example::class);

Route::get("/api/post/[id:int]","post",Example::class);

Route::post("/api/post","post_create",Example::class);

Route::delete("/api/post/[id:int]","post_delete",Example::class);

Route::put("/api/post/[id:int]","post_update",Example::class);

Route::get("/api/posts/search/[title]","post_search",Example::class);

Route::get("/api/post/[id:int]/image","post_image",Example::class);



