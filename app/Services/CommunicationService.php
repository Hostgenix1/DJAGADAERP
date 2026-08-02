<?php

namespace App\Services;

use App\Contracts\Repositories\CommunicationRepositoryInterface;
use App\Models\Communication;

class CommunicationService
{
    public function __construct(protected CommunicationRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Communication
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Communication
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Communication
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
