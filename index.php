<?php

use Simp\Router\Route;
use Simp\Router\Router\RouterRegister;

require_once "vendor/autoload.php";

require_once "Example.php";
require_once "Post.php";

$register = new RouterRegister();

$register->get('/','index', Route::entry(Example::class));

$register->get('/post/[post:'.Post::class.']','post', Route::entry(Example::class));

$register->get('/posts/listing', 'post_listing', Route::entry(Example::class));

$register->get('/[file:int]/post/file', 'post_listing', Route::entry(Example::class));


