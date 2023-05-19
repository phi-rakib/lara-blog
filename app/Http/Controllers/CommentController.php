<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentController extends ApiController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->commentRepository->getAllComments();
    }

    public function show($commentId)
    {
        return $this->commentRepository->commentsById($commentId, ['user:id,name']);
    }

    public function commentsByPostId($postId)
    {
        return $this->commentRepository->commentsByPostId($postId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $post
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        return $this->commentRepository->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        $this->commentRepository->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CommentRequest $request
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->commentRepository->delete($id);
    }
}
