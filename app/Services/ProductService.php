<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Product
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Product
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
