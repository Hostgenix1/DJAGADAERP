<?php

namespace App\Services;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class SupplierService
{
    public function __construct(protected SupplierRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Supplier
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Supplier
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Supplier
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
