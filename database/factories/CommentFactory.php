<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->paragraph(),
            'post_id' => rand(1, 10),
            'user_id' => rand(1, 10),
        ];
    }
}
