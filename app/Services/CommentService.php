<?php

namespace App\Services;

use App\Repositories\Comment\CommentRepositoryInterface;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAll()
    {
        return $this->commentRepository->getAllComments();
    }

    public function getById($commentId)
    {
        return $this->commentRepository->commentsById($commentId, ['user:id,name']);
    }

    public function getByPostId($postId)
    {
        return $this->commentRepository->commentsByPostId($postId);
    }

    public function create($data)
    {
        return $this->commentRepository->create($data);
    }

    public function update($data, $id)
    {
        $this->commentRepository->update($id, $data);
    }

    public function delete($id)
    {
        $this->commentRepository->delete($id);
    }
}

