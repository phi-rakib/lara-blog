<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function get($id);

    public function all();

    public function create($post);

    public function delete($id);

    public function update($id, $payload);
}
