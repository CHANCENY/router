<?php

class Post
{
    public $title;
    public $content;
    public $id;

    public function __construct(int $id) {
        $this->id = $id;
        $this->title = "My Post $id";
        $this->content = "This is my post content";
    }
}