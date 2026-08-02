<?php

namespace App\Services;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerService
{
    public function __construct(protected CustomerRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Customer
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Customer
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
