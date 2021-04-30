<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostRepository implements PostRepositoryInterface
{
    private function selectedColumns(): array
    {
        return ['id', 'title', 'body', 'user_id', 'created_at', 'updated_at'];
    }

    public function get($id)
    {
        return Post::findOrFail($id, $this->selectedColumns());
    }

    public function all()
    {
        return Post::all($this->selectedColumns());
    }

    public function create($post)
    {
        /**
         * @var User
         */
        $user = Auth::user();
        return $user->posts()->create($post);
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
