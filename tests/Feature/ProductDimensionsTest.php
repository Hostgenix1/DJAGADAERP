<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDimensionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_dimension_parts_render_into_the_printable_summary(): void
    {
        $product = app(ProductService::class)->create([
            'sku' => 'DIM-001',
            'name' => 'Boxed Widget',
            'length_cm' => 30,
            'width_cm' => 40,
            'height_cm' => 50,
        ]);

        $this->assertSame('30 x 40 x 50 cm', $product->dimensions);
        $this->assertSame('30.00', (string) $product->length_cm);
    }

    public function test_a_partial_dimension_still_produces_a_summary(): void
    {
        $product = app(ProductService::class)->create([
            'sku' => 'DIM-002',
            'name' => 'Long Widget',
            'length_cm' => 120.5,
        ]);

        $this->assertSame('120.5 cm', $product->dimensions);
    }

    public function test_updating_dimensions_recomputes_the_summary(): void
    {
        $product = app(ProductService::class)->create([
            'sku' => 'DIM-003',
            'name' => 'Widget',
            'length_cm' => 30,
        ]);

        app(ProductService::class)->update($product->id, [
            'length_cm' => 35,
            'width_cm' => 20,
            'height_cm' => 10,
        ]);

        $product->refresh();
        $this->assertSame('35 x 20 x 10 cm', $product->dimensions);
    }

    public function test_dimensions_of_a_product_without_parts_and_manual_value_are_left_alone(): void
    {
        $product = Product::create([
            'sku' => 'DIM-004',
            'name' => 'Legacy Widget',
            'dimensions' => 'approx 1 pallet',
        ]);

        $this->assertSame('approx 1 pallet', $product->dimensions);
    }
}
