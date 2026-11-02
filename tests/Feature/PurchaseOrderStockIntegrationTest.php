<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierBill;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\PurchaseOrderService;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PurchaseOrderStockIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    private Warehouse $warehouse;

    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'view-purchase-orders', 'create-purchase-orders', 'update-purchase-orders',
            'view-supplier-bills', 'create-supplier-bills', 'view-stock',
        ] as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo([
            'view-purchase-orders', 'create-purchase-orders', 'update-purchase-orders',
            'view-supplier-bills', 'create-supplier-bills', 'view-stock',
        ]);

        $this->warehouse = Warehouse::create(['name' => 'Main Warehouse', 'is_active' => true]);
        $this->supplier = Supplier::create(['company_name' => 'Acme Supplies']);
        $brand = Brand::create(['name' => 'Test Brand']);
        $category = ProductCategory::create(['name' => 'Test Category']);
        $this->product = Product::create(['sku' => 'STOCK-001', 'name' => 'Stocked Widget', 'brand_id' => $brand->id, 'category_id' => $category->id]);
    }

    private function createPurchaseOrder(): PurchaseOrder
    {
        return app(PurchaseOrderService::class)->createWithItems([
            'supplier_id' => $this->supplier->id,
            'po_date' => now()->toDateString(),
            'status' => 'draft',
            'vat_mode' => 'none',
            'vat_rate' => 0,
        ], [
            ['product_id' => $this->product->id, 'description' => 'Stocked Widget', 'qty' => 4, 'unit' => 'pcs', 'unit_price' => 5],
        ]);
    }

    public function test_marking_received_puts_goods_into_stock(): void
    {
        $po = $this->createPurchaseOrder();

        $this->actingAs($this->user)->post(route('purchase_orders.status', $po), ['status' => 'confirmed'])->assertSessionHasNoErrors();
        $this->actingAs($this->user)->post(route('purchase_orders.status', $po->fresh()), ['status' => 'received'])->assertSessionHasNoErrors();

        $po->refresh();
        $this->assertSame('received', $po->status);

        $this->assertSame(4.0, StockService::currentStock($this->product->id, $this->warehouse->id));

        $reference = 'PO:'.$po->number.':R1';
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'type' => 'in',
            'qty' => 4,
            'reference' => $reference,
            'unit_cost' => 5,
        ]);
    }

    public function test_cancelling_a_received_order_reverses_the_goods_receipt(): void
    {
        $po = $this->createPurchaseOrder();

        $this->actingAs($this->user)->post(route('purchase_orders.status', $po), ['status' => 'confirmed'])->assertSessionHasNoErrors();
        $this->actingAs($this->user)->post(route('purchase_orders.status', $po->fresh()), ['status' => 'received'])->assertSessionHasNoErrors();
        $this->actingAs($this->user)->post(route('purchase_orders.status', $po->fresh()), ['status' => 'cancelled'])->assertSessionHasNoErrors();

        $po->refresh();
        $this->assertSame('cancelled', $po->status);
        $this->assertSame(0.0, StockService::currentStock($this->product->id, $this->warehouse->id));

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'adjustment',
            'qty' => -4,
            'reference' => 'PO:'.$po->number.':C1',
        ]);
    }

    public function test_cancel_and_reconfirm_creates_a_new_receipt_cycle(): void
    {
        $po = $this->createPurchaseOrder();
        $status = route('purchase_orders.status', $po);

        $this->postStatus($po, 'confirmed');
        $this->postStatus($po, 'received');
        $this->postStatus($po, 'cancelled');
        $this->postStatus($po, 'draft');
        $this->postStatus($po, 'confirmed');
        $this->postStatus($po, 'received');

        $this->assertSame(4.0, StockService::currentStock($this->product->id, $this->warehouse->id));

        $reference = 'PO:'.$po->number.':R2';
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'qty' => 4,
            'reference' => $reference,
        ]);
    }

    public function test_without_a_warehouse_the_receipt_is_rejected(): void
    {
        Warehouse::query()->delete();

        $po = $this->createPurchaseOrder();
        $this->postStatus($po, 'confirmed');
        $this->postStatus($po, 'received');

        $po->refresh();
        $this->assertSame('confirmed', $po->status);
    }

    public function test_draft_and_cancelled_orders_cannot_be_billed(): void
    {
        $po = $this->createPurchaseOrder();

        $this->actingAs($this->user)
            ->post(route('supplier_bills.convert', $po))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('supplier_bills', 0);

        $this->postStatus($po->fresh(), 'confirmed');
        $this->postStatus($po->fresh(), 'received');
        $this->postStatus($po->fresh(), 'cancelled');
        $this->postStatus($po->fresh(), 'draft');
        $this->postStatus($po->fresh(), 'cancelled');

        $this->actingAs($this->user)
            ->post(route('supplier_bills.convert', $po->fresh()))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('supplier_bills', 0);
    }

    public function test_a_received_order_can_be_billed(): void
    {
        $po = $this->createPurchaseOrder();
        $this->postStatus($po, 'confirmed');
        $this->postStatus($po, 'received');

        $this->actingAs($this->user)
            ->post(route('supplier_bills.convert', $po->fresh()))
            ->assertSessionHas('success');

        $po->refresh();
        $this->assertSame('billed', $po->status);

        $bill = SupplierBill::first();
        $this->assertSame($po->id, $bill->purchase_order_id);
    }

    private function postStatus(PurchaseOrder $po, string $status): void
    {
        $this->actingAs($this->user)->post(route('purchase_orders.status', $po->fresh()), ['status' => $status]);
    }
}
