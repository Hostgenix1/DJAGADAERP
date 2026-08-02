<?php

namespace App\Repositories;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Models\Lead;

class LeadRepository extends BaseCrudRepository implements LeadRepositoryInterface
{
    public function model(): string
    {
        return Lead::class;
    }
}
