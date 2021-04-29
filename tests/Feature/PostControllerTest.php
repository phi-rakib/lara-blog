<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function getHeader()
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getAuth()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function testPostCreatedSuccessfully()
    {
        $this->getAuth();

        $post = Post::factory()->make();

        $this->postJson('/api/posts', $post->toArray(), $this->getHeader())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    "data" => [
                        "title" => strtoupper($post->title),
                        "body" => $post->body,
                    ],
                    "message" => "Created successfully",
                ]
            );
    }

    public function testPostCreateForMissingData()
    {
        $this->getAuth();

        $post = [
            'tiltle' => 'test title',
        ];

        $this->postJson('/api/posts', $post, $this->getHeader())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['body'],
            ]);
    }

    public function testPostRetrievedSuccessfully()
    {
        $this->getAuth();

        $posts = Post::factory()
            ->count(5)
            ->create()->toArray();

        $map = array_map(function ($post) {
            $post['created_at'] = date('Y-m-d H:i:s', strtotime($post['created_at']));
            unset($post['updated_at']);
            return $post;
        }, $posts);

        $this->getJson('/api/posts', $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $map,
                ]
            );
    }

    public function testPostRetrieveByIdSuccessfully()
    {
        $this->getAuth();

        $post = Post::factory()
            ->create()
            ->toArray();

        $post['created_at'] = date('Y-m-d H:i:s', strtotime($post['created_at']));
        unset($post['updated_at']);

        $this->getJson('/api/posts/' . $post['id'], $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $post,
                ]
            );
    }

    public function testPostRetrieveForMissingData()
    {
        $this->getAuth();

        $this->getJson('/api/posts/0', $this->getHeader())
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testPostDeletedSuccessfully()
    {
        $this->getAuth();

        $post = Post::factory()->create();
        $this->deleteJson('/api/posts/' . $post->id, $this->getHeader())
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }

    public function testPostDeleteForMissingData()
    {
        $this->getAuth();

        $this->deleteJson('/api/posts/0', $this->getHeader())
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testPostUpdatedSuccessfully()
    {
        $this->getAuth();

        $post = Post::factory()->create();
        $payload = Post::factory()->make()->toArray();
        $payload['id'] = $post->id;

        $this->putJson('/api/posts/' . $post->id, $payload, $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $payload,
                    "message" => "Updated successfully",
                ]
            );

    }

    public function testPostUpdateForInvalidData()
    {
        $this->getAuth();

        $this->putJson('/api/posts/1', [], $this->getHeader())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors']);

    }

    public function testPostUpdateForMissingData()
    {
        $this->getAuth();

        $payload = Post::factory()->make()->toArray();

        $this->putJson('/api/posts/0', $payload, $this->getHeader())
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

}
