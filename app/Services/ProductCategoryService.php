<?php

namespace App\Services;

use App\Contracts\Repositories\ProductCategoryRepositoryInterface;
use App\Models\ProductCategory;

class ProductCategoryService
{
    public function __construct(protected ProductCategoryRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?ProductCategory
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): ProductCategory
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): ProductCategory
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
