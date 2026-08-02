<?php

namespace App\Services;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Models\Lead;

class LeadService
{
    public function __construct(protected LeadRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Lead
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Lead
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Lead
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
