<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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

    public function createPost($user)
    {
        return Post::factory()
            ->for($user)
            ->create();
    }

    public function testCommentCreatedSuccessfully()
    {
        $post = $this->createPost(User::factory()->create());

        $this->getAuth($this->createUser());

        $comment = Comment::factory()->make();

        $this->postJson(route('posts.comments.store', ['post' => $post->id]), $comment->toArray(), $this->getHeader())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    "data" => ['body' => $comment->body],
                    "message" => "Comment Created successfully",
                ]
            );

    }

    public function testCommentCreateForMissingData()
    {
        $post = $this->createPost(User::factory()->create());

        $this->getAuth($this->createUser());

        $this->postJson(route('posts.comments.store', ['post' => $post->id]), [], $this->getHeader())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function testCommentRetrievedSuccessfully()
    {
        $post = $this->createPost(User::factory()->create());

        $user = User::factory()->create();
        $this->getAuth($user);

        $comments = Comment::factory()
            ->state(['post_id' => $post->id])
            ->count(5)
            ->for($user)
            ->create();

        $response = $this->getJson(route('posts.comments.index', ['post' => $post->id]), $this->getHeader());

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' => [
                            'id',
                            'body',
                            'post_id',
                            'user_id',
                            'created_at',
                            'updated_at',
                            'user' => ['id', 'name'],
                        ],
                    ],
                ]
            );

    }

    public function testCommentRetrieveForMissingData()
    {
        $this->getAuth($this->createUser());

        $response = $this->getJson(route('posts.comments.index', ['post' => 0]), $this->getHeader());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testCommentUpdatedSuccessfully()
    {
        $post = $this->createPost(User::factory()->create());

        $user = User::factory()->create();
        $this->getAuth($user);

        $comment = Comment::factory()
            ->state(['post_id' => $post->id])
            ->for($user)
            ->create();

        $updatedComment = Comment::factory()
            ->make()
            ->toArray();

        $response = $this->putJson(route('comments.update', ['comment' => $comment->id]), $updatedComment, $this->getHeader());

        $updatedComment['id'] = $comment->id;

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "data" => $updatedComment,
                    "message" => "Comment Updated successfully",
                ]
            );

    }

    public function testCommentUpdateForMissingData()
    {
        $this->getAuth($this->createUser());

        $comment = Comment::factory()
            ->make()
            ->toArray();

        $response = $this->putJson(route('comments.update', ['comment' => 0]), $comment, $this->getHeader());

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCommentDeletedSuccessfully()
    {
        $post = Post::factory()
            ->for(User::factory()->create())
            ->create();

        $user = User::factory()->create();
        $this->getAuth($user);

        $comment = Comment::factory()
            ->state(['post_id' => $post->id])
            ->for($user)
            ->create();

        $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]), [], $this->getHeader());

        $response
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();

    }

    public function testCommentDeleteForMissingData()
    {
        $this->getAuth(User::factory()->create());
        $response = $this->deleteJson(route('comments.destroy', ['comment' => 0]), [], $this->getHeader());

        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testPostDeletedByAnotherUser()
    {
        $post = $this->createPost(User::factory()->create());

        $user = User::factory()->create();
        $this->getAuth($user);

        $user2 = User::factory()->create();
        $comment = Comment::factory()
            ->state(['post_id' => $post->id])
            ->for($user2)
            ->create();

        $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]), [], $this->getHeader());

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testPostUpdatedByAnotherUser()
    {
        $post = $this->createPost(User::factory()->create());

        $user = User::factory()->create();
        $this->getAuth($user);

        $user2 = User::factory()->create();

        $comment = Comment::factory()
            ->state(['post_id' => $post->id])
            ->for($user2)
            ->create();

        $updatedComment = Comment::factory()
            ->make()
            ->toArray();

        $response = $this->putJson(route('comments.update', ['comment' => $comment->id]), $updatedComment, $this->getHeader());

        $updatedComment['id'] = $comment->id;

        $response->assertStatus(Response::HTTP_FORBIDDEN);

    }
}
