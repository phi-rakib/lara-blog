<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Comment());
    }

    public function getAllComments()
    {
        return parent::all(['user:id,name'])->get();
    }

    public function commentsById($id, $includes = [])
    {
        return parent::show($id, $includes)->firstOrFail();
    }

    public function commentsByPostId($postId)
    {
        return Comment::where('post_id', $postId)
            ->get();
    }
}
