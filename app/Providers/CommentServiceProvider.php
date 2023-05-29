<?php

namespace App\Providers;

use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\CommentService;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('comment', function($app) {
            return new CommentService($app->make(CommentRepositoryInterface::class));
        });
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
