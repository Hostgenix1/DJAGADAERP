<?php

namespace Tests\Feature;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PurchaseRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'view-purchase-requests', 'create-purchase-requests', 'update-purchase-requests',
            'delete-purchase-requests', 'approve-purchase-requests',
            'view-purchase-orders', 'create-purchase-orders',
        ] as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo([
            'view-purchase-requests', 'create-purchase-requests', 'update-purchase-requests',
            'delete-purchase-requests', 'approve-purchase-requests',
            'view-purchase-orders', 'create-purchase-orders',
        ]);

        $this->supplier = Supplier::create(['company_name' => 'Acme Supplies']);
    }

    public function test_a_request_can_be_created_submitted_approved_and_converted_to_a_purchase_order(): void
    {
        $response = $this->actingAs($this->user)->post(route('purchase_requests.store'), [
            'supplier_id' => $this->supplier->id,
            'department' => 'Operations',
            'needed_by' => now()->addDays(7)->toDateString(),
            'priority' => 'high',
            'justification' => 'Restock packaging material',
            'items' => [
                ['description' => 'Cartons', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 2.5],
            ],
        ]);

        $request = PurchaseRequest::firstOrFail();

        $response->assertRedirect(route('purchase_requests.show', $request));
        $this->assertSame('draft', $request->status);
        $this->assertSame(25.0, (float) $request->estimated_total);
        $this->assertSame(25.0, (float) $request->items()->first()->line_total);

        $this->actingAs($this->user)->post(route('purchase_requests.submit', $request))->assertSessionHasNoErrors();
        $this->assertSame('pending', $request->fresh()->status);

        $this->actingAs($this->user)->post(route('purchase_requests.approve', $request))->assertSessionHasNoErrors();
        $request->refresh();
        $this->assertSame('approved', $request->status);
        $this->assertSame($this->user->id, $request->approved_by);

        $this->actingAs($this->user)->post(route('purchase_requests.convert', $request))->assertSessionHasNoErrors();

        $request->refresh();
        $this->assertSame('converted', $request->status);

        $purchaseOrder = PurchaseOrder::findOrFail($request->purchase_order_id);
        $this->assertSame($this->supplier->id, $purchaseOrder->supplier_id);
        $this->assertSame(25.0, (float) $purchaseOrder->total);
        $this->assertCount(1, $purchaseOrder->items);
    }

    public function test_only_approved_requests_can_be_converted(): void
    {
        $request = PurchaseRequest::create([
            'number' => 'PR/2026/00001',
            'requested_by' => $this->user->id,
            'supplier_id' => $this->supplier->id,
            'status' => 'draft',
        ]);

        $this->actingAs($this->user)
            ->post(route('purchase_requests.convert', $request))
            ->assertSessionHas('error');

        $this->assertSame('draft', $request->fresh()->status);
        $this->assertDatabaseCount('purchase_orders', 0);
    }
}
