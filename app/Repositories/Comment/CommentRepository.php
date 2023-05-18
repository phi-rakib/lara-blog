<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{

    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function all()
    {
        return Comment::with('user:id,name')
            ->get();
    }

    public function create($data, $postId)
    {
        $comment = new Comment($data);
        $comment->user()->associate($this->authRepository->getAuthId());
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

    public function commentsByPostId($postId)
    {
        return Comment::where('post_id', $postId)
            ->get();
    }
}
