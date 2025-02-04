<?php

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
        if($args['route_name'] === 'index') {
            return new Response("<h1>Post Simple Api</h1>");
        }

        elseif ($args['route_name'] === 'posts') {
            return new Response($this->posts,200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'post') {
            $post = array_filter($this->posts, fn ($post) => $post['id'] === $args['request']->query->get('id'));
            return new Response(array_values($post),200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'post_create') {
            $data = json_decode($args['request']->payload->getContent(), true);
            $data['id'] = time();
            $this->posts[] = $data;
            file_put_contents(__DIR__ . '/posts.json', json_encode($this->posts, JSON_PRETTY_PRINT));
            return new Response($data,200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'post_delete') {
            $id = $args['request']->query->get('id');
            $this->posts = array_filter($this->posts, fn ($post) => $post['id'] !== $id);
            file_put_contents(__DIR__ . '/posts.json', json_encode($this->posts, JSON_PRETTY_PRINT));
            return new Response(['status'=>200, 'msg'=>'deleted post'],200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'post_update') {
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
            return new Response(['status'=>$updated],200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'post_search') {
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
            return new Response($matches,200, ['Content-Type' => 'application/json']);
        }

        elseif ($args['route_name'] === 'api_help') {
            return new Response($args['request']->query->get('help_title')->content , 200);
        }

        elseif ($args['route_name'] === 'post_image') {

            $id = $args['request']->query->get('id');
            $post_found = array_filter($this->posts, fn ($post) => $post['id'] === $id);
            if ($post = reset($post_found)) {
                $image = $post['image'] ?? null;
                if ($image) {
                    $content = file_get_contents($image);
                    $base64 = "data:application/octet-stream;base64," . base64_encode($content);
                    return new Response(['image'=>$base64],200, ['Content-Type' => "application/json"]);
                }
            }
            return new Response(null,404, ['Content-Type' => 'application/json']);
        }

        return new Response([], 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}