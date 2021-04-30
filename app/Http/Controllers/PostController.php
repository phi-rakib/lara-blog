<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Response;
use App\Repositories\Post\PostRepositoryInterface;

class PostController extends ApiController
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        return PostResource::collection($this->postRepository->all());
    }

    public function store(StorePostRequest $request)
    {
        $payload = $request->validated();

        return (new PostResource($this->postRepository->create($payload)))
            ->additional(["message" => "Created successfully"]);
    }

    public function show($id)
    {
        return new PostResource($this->postRepository->get($id));
    }

    public function update(StorePostRequest $request, $id)
    {
        $payload = $request->validated();

        return (new PostResource($this->postRepository->update($id, $payload)))
            ->additional(["message" => "Updated successfully"]);
    }

    public function destroy($id)
    {
        $this->postRepository->delete($id);
        return $this->respondNoContent();
    }
}
