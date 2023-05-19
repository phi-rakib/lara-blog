<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($includes = []): Builder
    {
        return $this->model::with($includes);
    }

    public function show($id, $includes = []): Builder
    {
        return $this->model::whereId($id)
            ->with($includes);
    }

    public function findWhere($column, $value): Builder
    {
        return $this->model::where($column, $value);
    }

    public function create($data): Model
    {
        return $this->model::create($data);
    }

    public function update($id, $data): int
    {
        return $this->model::whereId($id)->update($data);
    }

    public function delete($id): void
    {
        $this->model::whereId($id)->delete();
    }
}
