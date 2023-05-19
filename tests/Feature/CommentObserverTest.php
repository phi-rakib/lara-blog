<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Observers\CommentObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CommentObserverTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCommentCreate()
    {
        $this->withoutExceptionHandling();

        $this->instance(CommentObserver::class, Mockery::mock(CommentObserver::class, function (MockInterface $mock) {
            $mock->shouldReceive('creating')->once();
        }));

        Comment::factory()
            ->state([
                'post_id' => rand(1, 10),
                'user_id' => rand(1, 10),
            ])
            ->create();
    }
}
