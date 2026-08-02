<?php

namespace App\Repositories;

use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Models\Brand;

class BrandRepository extends BaseCrudRepository implements BrandRepositoryInterface
{
    public function model(): string
    {
        return Brand::class;
    }
}
