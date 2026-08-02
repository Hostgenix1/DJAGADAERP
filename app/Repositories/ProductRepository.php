<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository extends BaseCrudRepository implements ProductRepositoryInterface
{
    public function model(): string
    {
        return Product::class;
    }
}
