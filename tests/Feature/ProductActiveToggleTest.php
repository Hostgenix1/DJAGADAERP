<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProductActiveToggleTest extends TestCase
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

    public function test_a_product_created_from_the_form_defaults_to_active(): void
    {
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'Default Active Product',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Default Active Product', 'is_active' => 1]);
    }

    public function test_products_can_be_enabled_and_disabled_from_the_list_actions(): void
    {
        $product = Product::create(['sku' => 'TOG-001', 'name' => 'Toggle Widget']);

        $this->assertTrue($product->fresh()->is_active);

        $response = $this->actingAs($this->user)->post(route('products.toggle-active', $product));

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => 0]);

        $this->actingAs($this->user)->post(route('products.toggle-active', $product->fresh()));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => 1]);
    }
}
