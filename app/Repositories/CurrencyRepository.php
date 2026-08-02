<?php

namespace App\Repositories;

use App\Contracts\Repositories\CurrencyRepositoryInterface;
use App\Models\Currency;

class CurrencyRepository extends BaseCrudRepository implements CurrencyRepositoryInterface
{
    public function model(): string
    {
        return Currency::class;
    }
}
