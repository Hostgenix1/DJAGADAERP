<?php

namespace App\Services;

use App\Contracts\Repositories\CurrencyRepositoryInterface;
use App\Models\Currency;

class CurrencyService
{
    public function __construct(protected CurrencyRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Currency
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Currency
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Currency
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
