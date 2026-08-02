<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function model(): string;

    public function query();

    public function all(): Collection;

    public function find(int|string $id): ?Model;

    public function findOrFail(int|string $id): Model;

    public function first(array $attributes): ?Model;

    public function create(array $attributes): Model;

    public function update(Model|int|string $model, array $attributes): Model;

    public function delete(Model|int|string $model): bool;

    public function exists(array $attributes): bool;

    public function count(): int;
}