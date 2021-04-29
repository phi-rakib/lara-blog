<?php

namespace App\Repositories\Post;

use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function get($id)
    {
        return Post::findOrFail($id);
    }

    public function all()
    {
        return Post::all(['id', 'title', 'body', 'created_at']);
    }

    public function create($post)
    {
        return Post::create($post);
    }

    public function delete($id)
    {
        $post = $this->get($id);
        $post->delete();
    }

    public function update($id, $payload)
    {
        $post = $this->get($id);
        $post->update($payload);
        return $post;
    }
}
