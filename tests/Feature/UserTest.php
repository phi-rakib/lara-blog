<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testSchema()
    {
        $columns = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        $this->assertTrue(Schema::hasTable('users'));
        
        $this->assertTrue(Schema::hasColumns('users', $columns));
    }

    public function testPostsRelationship()
    {
        $user = User::factory()->create();

        $this->assertEquals(0, $user->posts()->count());

        $post = Post::factory()
            ->state([
                'user_id' => $user->id
            ])
            ->create();

        $this->assertTrue($user->posts->contains($post));

        $this->assertEquals(1, $user->posts()->count());

        $this->assertInstanceOf(Collection::class, $user->posts);
    }

    public function testCommentsRelationship()
    {
        Event::fake();

        $user = User::factory()->create();

        $this->assertEquals(0, $user->comments()->count());

        $comment = Comment::factory()
        ->state([
            'post_id' => 1,
            'user_id' => $user->id,
        ])->create();
        
        $this->assertTrue($user->comments->contains($comment));

        $this->assertEquals(1, $user->comments()->count());

        $this->assertInstanceOf(Collection::class, $user->comments);
    }
}
