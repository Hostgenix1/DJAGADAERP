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

            $this->syncDimensions($product);

            return $product;
        });
    }

    public function update(int $id, array $attributes): Product
    {
        $product = $this->repository->update($id, $attributes);

        if (array_intersect_key(array_flip(['length_cm', 'width_cm', 'height_cm']), $attributes)) {
            $product->refresh();
            $this->syncDimensions($product);
        }

        return $product;
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

    /**
     * Fill the printable `dimensions` summary from the structured
     * length / width / height values, e.g. "30 x 40 x 50 cm".
     * When none of the parts is set, the free-text value is left untouched.
     */
    protected function syncDimensions(Product $product): void
    {
        $parts = [];

        foreach (['length_cm', 'width_cm', 'height_cm'] as $field) {
            if ($product->$field !== null) {
                $parts[] = self::trimNumber((float) $product->$field);
            }
        }

        if ($parts === []) {
            return;
        }

        $product->dimensions = implode(' x ', $parts).' cm';
        $product->save();
    }

    /**
     * Format 30.00 as "30" and 30.50 as "30.5".
     */
    protected static function trimNumber(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}
