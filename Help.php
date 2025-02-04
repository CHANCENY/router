<?php

require_once "vendor/autoload.php";
class Help
{

    private array $helps = [
        'index' => 'Returns the homepage or landing page content.',

        'posts' => 'Retrieves a list of all posts.',

        'post' => 'Retrieves the details of a specific post. 
               Parameters:
               - id (int): The ID of the post to retrieve.',

        'post_create' => 'Creates a new post.
                      Method: POST
                      Request Body:
                      - title (string): The title of the post.
                      - content (string): The body content of the post.',

        'post_delete' => 'Deletes a specific post.
                      Method: DELETE
                      Parameters:
                      - id (int): The ID of the post to delete.',

        'post_update' => 'Updates an existing post.
                      Method: PUT
                      Parameters:
                      - id (int): The ID of the post to update.
                      Request Body:
                      - title (string, optional): The updated title.
                      - content (string, optional): The updated content.',

        'post_search' => 'Searches for posts by title.
                      Method: GET
                      Parameters:
                      - title (string): The title (or partial title) to search for.'
    ];

    public string $content;

    public function __construct(string $title)
    {
        $this->content = nl2br($this->helps[$title] ?? 'Not help found');
    }
}