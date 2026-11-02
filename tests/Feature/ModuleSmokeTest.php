<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurchaseRequest;
use App\Models\Quote;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ModuleSmokeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'view-dashboard', 'view-purchase-requests', 'create-purchase-requests',
            'view-products', 'view-shipments', 'view-quotes',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo($permissions);
    }

    public function test_the_new_and_extended_pages_render(): void
    {
        $brand = Brand::create(['name' => 'Smoke Brand']);
        $category = ProductCategory::create(['name' => 'Smoke Category']);

        $product = Product::create([
            'sku' => 'SMOKE-001',
            'name' => 'Smoke Product',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $customer = Customer::create(['company_name' => 'Smoke Customer']);

        $shipment = Shipment::create([
            'number' => 'SHP-00001',
            'customer_id' => $customer->id,
            'shipping_method' => 'sea',
            'status' => 'preparing',
        ]);

        $quote = Quote::create([
            'number' => 'QT/2026/00001',
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'status' => 'sent',
            'revision' => 1,
            'vat_mode' => 'none',
        ]);

        $purchaseRequest = PurchaseRequest::create([
            'number' => 'PR/2026/00001',
            'requested_by' => $this->user->id,
            'department' => 'Operations',
            'status' => 'draft',
        ]);

        $this->actingAs($this->user)->get(route('dashboard'))->assertOk();
        $this->actingAs($this->user)->get(route('purchase_requests.index'))->assertOk();
        $this->actingAs($this->user)->get(route('purchase_requests.create'))->assertOk();
        $this->actingAs($this->user)->get(route('purchase_requests.show', $purchaseRequest))->assertOk();
        $this->actingAs($this->user)->get(route('products.show', $product))->assertOk();
        $this->actingAs($this->user)->get(route('shipments.show', $shipment))->assertOk();
        $this->actingAs($this->user)->get(route('quotes.show', $quote))->assertOk();
    }
}
