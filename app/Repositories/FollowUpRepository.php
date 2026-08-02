<?php

namespace App\Repositories;

use App\Contracts\Repositories\FollowUpRepositoryInterface;
use App\Models\FollowUp;

class FollowUpRepository extends BaseCrudRepository implements FollowUpRepositoryInterface
{
    public function model(): string
    {
        return FollowUp::class;
    }
}
