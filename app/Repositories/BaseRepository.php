<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    abstract public function model(): string;

    public function newModel(): Model
    {
        $model = $this->model();

        return new $model;
    }

    public function query(): Builder
    {
        return $this->newModel()->newQuery();
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function find(int|string $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function first(array $attributes): ?Model
    {
        return $this->query()->where($attributes)->first();
    }

    public function create(array $attributes): Model
    {
        return $this->query()->create($attributes);
    }

    public function update(Model|int|string $model, array $attributes): Model
    {
        if (! $model instanceof Model) {
            $model = $this->findOrFail($model);
        }

        $model->update($attributes);

        return $model;
    }

    public function delete(Model|int|string $model): bool
    {
        if (! $model instanceof Model) {
            $model = $this->findOrFail($model);
        }

        return (bool) $model->delete();
    }

    public function exists(array $attributes): bool
    {
        return $this->query()->where($attributes)->exists();
    }

    public function count(): int
    {
        return $this->query()->count();
    }
}