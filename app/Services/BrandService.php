<?php

namespace App\Services;

use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Models\Brand;

class BrandService
{
    public function __construct(protected BrandRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Brand
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Brand
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Brand
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
