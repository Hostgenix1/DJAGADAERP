<?php

namespace App\Repositories;

use App\Contracts\Repositories\CommunicationRepositoryInterface;
use App\Models\Communication;

class CommunicationRepository extends BaseCrudRepository implements CommunicationRepositoryInterface
{
    public function model(): string
    {
        return Communication::class;
    }
}
