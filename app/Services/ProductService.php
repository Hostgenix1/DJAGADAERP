<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $repository) {}

    public function query()
    {
        return $this->repository->query();
    }

    public function find(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function create(array $attributes): Product
    {
        return DB::transaction(function () use ($attributes) {
            $product = $this->repository->create($attributes);

            if (empty($product->sku)) {
                $product->sku = $this->generateSku($product->id);
                $product->save();
            }

            return $product;
        });
    }

    public function update(int $id, array $attributes): Product
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Auto-generate a unique SKU for a newly created product, e.g. SKU-00012.
     */
    protected function generateSku(int $productId): string
    {
        $base = 'SKU-'.str_pad((string) $productId, 5, '0', STR_PAD_LEFT);
        $candidate = $base;
        $suffix = 1;

        while (Product::query()->where('sku', $candidate)->exists()) {
            $candidate = $base.'-'.(++$suffix);
        }

        return $candidate;
    }
}
