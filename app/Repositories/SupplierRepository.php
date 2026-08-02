<?php

namespace App\Repositories;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class SupplierRepository extends BaseCrudRepository implements SupplierRepositoryInterface
{
    public function model(): string
    {
        return Supplier::class;
    }
}
