<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerRepository extends BaseCrudRepository implements CustomerRepositoryInterface
{
    public function model(): string
    {
        return Customer::class;
    }
}
