<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function all($postId);

    public function create($data, $postId);

    public function delete($id);

    public function update($id, $payload);
}
