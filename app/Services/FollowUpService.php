<?php

namespace App\Services;

use App\Contracts\Repositories\FollowUpRepositoryInterface;
use App\Models\FollowUp;

class FollowUpService
{
    public function __construct(protected FollowUpRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?FollowUp
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): FollowUp
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): FollowUp
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
