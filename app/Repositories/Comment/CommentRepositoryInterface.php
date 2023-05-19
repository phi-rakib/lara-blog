<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function getAllComments();

    public function create($data);

    public function delete($id);

    public function update($id, $data);

    public function commentsById($commentId, array $includes);

    public function commentsByPostId($postId);
}
