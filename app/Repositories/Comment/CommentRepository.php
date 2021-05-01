<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{

    public function all($postId)
    {
        return Comment::with('user:id,name')
            ->where('post_id', $postId)
            ->get();
    }

    public function create($data, $postId)
    {
        $comment = new Comment($data);
        $comment->user()->associate(auth()->id());
        $comment->post()->associate($postId);
        $comment->save();
        return $comment;
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }

    public function update($id, $data)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }
}
