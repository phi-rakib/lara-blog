<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\Post\PostRepositoryInterface;

class PostController extends ApiController
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->modelName = "Post";
    }

    public function index()
    {
        return PostResource::collection($this->postRepository->all());
    }

    public function store(PostRequest $request)
    {
        $payload = $request->validated();

        return (new PostResource($this->postRepository->create($payload)))
            ->additional($this->messageCreated($this->modelName));
    }

    public function show($id)
    {
        return new PostResource($this->postRepository->get($id));
    }

    public function update(PostRequest $request, $id)
    {
        $payload = $request->validated();

        return (new PostResource($this->postRepository->update($id, $payload)))
            ->additional($this->messageUpdated($this->modelName));
    }

    public function destroy(PostRequest $request, $id)
    {
        $this->postRepository->delete($id);
        return $this->respondNoContent();
    }
}
