<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProductSkuTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['view-products', 'create-products', 'update-products'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo(['view-products', 'create-products', 'update-products']);
    }

    public function test_creating_a_product_without_sku_auto_generates_a_sequential_one(): void
    {
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'Auto SKU Product',
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::query()->where('name', 'Auto SKU Product')->firstOrFail();
        $this->assertMatchesRegularExpression('/^SKU-\d{5}(-\d+)?$/', $product->sku);
    }

    public function test_a_manually_typed_sku_is_kept(): void
    {
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'sku' => 'MY-OWN-SKU',
            'name' => 'Manual Product',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'MY-OWN-SKU', 'name' => 'Manual Product']);
    }

    public function test_duplicate_sku_is_rejected_on_create(): void
    {
        Product::create(['sku' => 'DUP-001', 'name' => 'First']);

        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'sku' => 'DUP-001',
            'name' => 'Second',
        ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_sku_can_be_edited_and_must_stay_unique(): void
    {
        Product::create(['sku' => 'FIRST-001', 'name' => 'First']);
        $second = Product::create(['sku' => 'SECOND-001', 'name' => 'Second']);

        $response = $this->actingAs($this->user)->put(route('products.update', $second), [
            'sku' => 'EDITED-001',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'EDITED-001', 'name' => 'Second']);

        $response = $this->actingAs($this->user)->put(route('products.update', $second), [
            'sku' => 'FIRST-001',
        ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_the_next_sku_suggestion_endpoint_returns_a_sku(): void
    {
        Product::create(['sku' => 'FIRST-001', 'name' => 'First']);

        $response = $this->actingAs($this->user)->getJson(route('products.sku.next'));

        $response->assertOk();
        $this->assertStringStartsWith('SKU-', $response->json('sku'));
    }
}
