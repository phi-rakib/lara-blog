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

    public function getAuth($user)
    {
        Sanctum::actingAs(
            $user,
            ['*']
        );
    }

    public function createUser()
    {
        return User::factory()->create();
    }

    public function testPostCreatedSuccessfully()
    {
        $this->getAuth($this->createUser());

        $post = Post::factory()->make();

        $this->postJson(route('posts.store'), $post->toArray(), $this->getHeader())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    "data" => [
                        "title" => strtoupper($post->title),
                        "body" => $post->body,
                    ],
                    "message" => "Post Created successfully",
                ]
            );
    }

    public function testPostCreateForMissingData()
    {
        $this->getAuth($this->createUser());

        $post = [
            'tiltle' => 'test title',
        ];

        $this->postJson(route('posts.store'), $post, $this->getHeader())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['body'],
            ]);
    }

    public function testPostRetrievedSuccessfully()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $posts = Post::factory()
            ->count(5)
            ->for($user)
            ->create();

        $this->getJson(route('posts.index'), $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $posts->toArray(),
                ]
            );
    }

    public function testPostRetrieveByIdSuccessfully()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $this->getJson(route('posts.show', ['post' => $post->id]), $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $post->toArray(),
                ]
            );
    }

    public function testPostRetrieveForMissingData()
    {
        $this->getAuth($this->createUser());

        $this->getJson(route('posts.show', ['post' => 0]), $this->getHeader())
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testPostDeletedSuccessfully()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $this->deleteJson(route('posts.destroy', ['post' => $post->id]), $this->getHeader())
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }

    public function testPostDeleteForMissingData()
    {
        $this->getAuth($this->createUser());

        $this->deleteJson(route('posts.destroy', ['post' => 0]), $this->getHeader())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testPostUpdatedSuccessfully()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $payload = Post::factory()
            ->for($user)
            ->make()
            ->toArray();

        $payload['id'] = $post->id;

        $this->putJson(route('posts.update', ['post' => $post->id]), $payload, $this->getHeader())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $payload,
                    "message" => "Post Updated successfully",
                ]
            );
    }

    public function testPostUpdateForInvalidData()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $this->putJson(route('posts.update', ['post' => $post->id]), [], $this->getHeader())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors']);

    }

    public function testPostUpdateForMissingData()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $payload = Post::factory()
            ->for($user)
            ->make()
            ->toArray();

        $this->putJson(route('posts.update', ['post' => 0]), $payload, $this->getHeader())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testPostUpdatedByAnotherUser()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $user2 = User::factory()->create();
        $this->getAuth($user2);

        $payload = Post::factory()
            ->for($user2)
            ->make()
            ->toArray();

        $payload['id'] = $post->id;

        $this->putJson(route('posts.update', ['post' => $post->id]), $payload, $this->getHeader())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testPostDeleteByAnotherUser()
    {
        $user = User::factory()->create();
        $this->getAuth($user);

        $post = Post::factory()
            ->for($user)
            ->create();

        $user2 = User::factory()->create();
        $this->getAuth($user2);

        $payload['id'] = $post->id;

        $this->deleteJson(route('posts.destroy', ['post' => $post->id]), $this->getHeader())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

}
