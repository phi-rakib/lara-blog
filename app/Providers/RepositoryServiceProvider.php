<?php

namespace App\Providers;

use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Post\PostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        PostRepositoryInterface::class => PostRepository::class,
        CommentRepositoryInterface::class => CommentRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
