<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\PackTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PackTypeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['view-settings', 'update-settings', 'view-products', 'create-products'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo(['view-settings', 'update-settings', 'view-products', 'create-products']);
    }

    public function test_a_pack_type_can_be_added_from_settings(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.settings.pack_types.store'), [
            'name' => 'drum',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pack_types', ['name' => 'drum']);
        $this->assertContains('drum', PackTypes::all());
    }

    public function test_the_four_defaults_are_seeded_on_fresh_database(): void
    {
        $this->assertSame(['carton', 'box', 'unit', 'pallet'], PackTypes::all());
    }

    public function test_a_product_can_use_a_custom_pack_type(): void
    {
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'sku' => 'PT-001',
            'name' => 'Custom Pack Product',
            'pack_type' => 'drum',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'PT-001', 'pack_type' => 'drum']);
    }
}
