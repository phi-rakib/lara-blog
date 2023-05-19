<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $commentRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->commentRepository = app()->make(CommentRepository::class);
    }

    private function createComment(): Comment
    {
        Event::fake();

        return Comment::factory()
            ->state(
                [
                    'user_id' => rand(1, 10),
                    'post_id' => rand(1, 10),
                ]
            )->create();
    }

    public function testCommentsById()
    {
        $comment = $this->createComment();

        $this->assertEquals(
            $comment->toArray(),
            $this->commentRepository->commentsById($comment->id)->toArray()
        );
    }

    public function testCreate()
    {
        Event::fake();

        $comment = Comment::factory()
            ->state(
                [
                    'user_id' => rand(1, 10),
                    'post_id' => rand(1, 10),
                ]
            )->make();

        $this->commentRepository->create($comment->toArray());

        $this->assertDatabaseHas('comments', $comment->toArray());
    }

    public function testUpdate()
    {
        $comment = $this->createComment();

        $comment->body = 'New body';

        $this->commentRepository->update($comment->id, $comment->toArray());

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'New body',
        ]);
    }

    public function testDelete()
    {
        $comment = $this->createComment();

        $this->commentRepository->delete($comment->id);

        $this->assertDatabaseMissing('comments', $comment->toArray());
    }

    public function testCommentsByPostId()
    {
        $comment = $this->createComment();

        $this->assertEquals(
            $comment->toArray(),
            $this->commentRepository->commentsByPostId($comment->post_id)
                ->first()
                ->toArray()
        );
    }

    public function testGetAllComments()
    {
        Event::fake();

        Comment::factory()
            ->state(
                [
                    'user_id' => rand(1, 10),
                    'post_id' => rand(1, 10),
                ]
            )
            ->count(10)
            ->create();

        $this->assertDatabaseCount('comments', $this->commentRepository->getAllComments()->count());
    }
}
