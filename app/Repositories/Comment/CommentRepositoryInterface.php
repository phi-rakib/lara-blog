<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function all();

    public function create($data, $postId);

    public function delete($id);

    public function update($id, $payload);

    public function commentsByPostId($postId);
}
