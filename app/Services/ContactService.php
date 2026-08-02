<?php

namespace App\Services;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;

class ContactService
{
    public function __construct(protected ContactRepositoryInterface $repository)
    {
    }

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Contact
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Contact
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Contact
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
