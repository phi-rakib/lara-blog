<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Facades\CommentFacade;
use Illuminate\Support\Facades\Log;

class CommentController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        return CommentFacade::getAll();
    }

    public function show($commentId)
    {
        return CommentFacade::getById($commentId);
    }

    public function commentsByPostId($postId)
    {
        return CommentFacade::getByPostId($postId);
    }

    public function store(CommentRequest $request)
    {
        return CommentFacade::create($request->all());
    }

    public function update(CommentRequest $request, $id)
    {
        CommentFacade::update($id, $request->all());
    }

    public function destroy($id)
    {
        CommentFacade::delete($id);
    }
}
