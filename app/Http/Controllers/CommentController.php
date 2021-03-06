<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentController extends ApiController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->middleware('auth:sanctum')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($postId)
    {
        $comments = $this->commentRepository->all($postId);
        return $comments->isEmpty() ? $this->respondNotFound() : CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $post
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, $postId)
    {
        return (new CommentResource($this->commentRepository->create($request->validated(), $postId)))
            ->additional($this->messageCreated("Comment"));
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
        return (new CommentResource($this->commentRepository->update($id, $request->validated())))
            ->additional($this->messageUpdated("Comment"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CommentRequest $request
     * @param int $id
     * @return void
     */
    public function destroy(CommentRequest $request, $id)
    {
        $this->commentRepository->delete($id);
        return $this->respondNoContent();
    }
}
