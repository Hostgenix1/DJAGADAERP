<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SalesListFiltersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Customer $first;

    private Customer $second;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = ['view-invoices', 'view-orders', 'view-shipments', 'view-quotes'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo($permissions);

        $this->first = Customer::create(['company_name' => 'Alpha Trading']);
        $this->second = Customer::create(['company_name' => 'Beta LLC']);

        Invoice::create([
            'number' => 'INV-001', 'type' => 'commercial', 'customer_id' => $this->first->id,
            'invoice_date' => '2026-09-01', 'status' => 'paid', 'total' => 1000,
        ]);
        Invoice::create([
            'number' => 'INV-002', 'type' => 'proforma', 'customer_id' => $this->second->id,
            'invoice_date' => '2026-10-05', 'status' => 'draft', 'total' => 500,
        ]);

        Order::create([
            'number' => 'ORD-001', 'customer_id' => $this->first->id,
            'order_date' => '2026-09-10', 'status' => 'confirmed', 'total' => 100,
        ]);
        Order::create([
            'number' => 'ORD-002', 'customer_id' => $this->second->id,
            'order_date' => '2026-10-11', 'status' => 'draft', 'total' => 200,
        ]);

        Shipment::create([
            'number' => 'SHP-001', 'customer_id' => $this->first->id,
            'shipping_method' => 'sea', 'status' => 'in_transit', 'shipped_at' => '2026-09-15 10:00:00',
        ]);
        Shipment::create([
            'number' => 'SHP-002', 'customer_id' => $this->second->id,
            'shipping_method' => 'air', 'status' => 'preparing',
        ]);

        Quote::create([
            'number' => 'QT-001', 'customer_id' => $this->first->id,
            'date' => '2026-09-05', 'status' => 'sent', 'revision' => 1,
        ]);
        Quote::create([
            'number' => 'QT-002', 'customer_id' => $this->second->id,
            'date' => '2026-10-06', 'status' => 'draft', 'revision' => 1,
        ]);
    }

    public function test_invoices_can_be_filtered_by_customer_and_type(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('invoices.datatable', ['customer_id' => $this->first->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('INV-001', $response->json('data.0.number'));

        $response = $this->actingAs($this->user)->getJson(
            route('invoices.datatable', ['type' => 'proforma'])
        );

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('INV-002', $response->json('data.0.number'));
    }

    public function test_invoices_can_be_filtered_by_status_and_date_range(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('invoices.datatable', [
            'status' => 'paid', 'from' => '2026-09-01', 'to' => '2026-09-30',
        ]));

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('INV-001', $response->json('data.0.number'));
    }

    public function test_orders_can_be_filtered_by_customer_status_and_date_range(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('orders.datatable', ['customer_id' => $this->second->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('ORD-002', $response->json('data.0.number'));

        $response = $this->actingAs($this->user)->getJson(route('orders.datatable', [
            'status' => 'confirmed', 'from' => '2026-09-01', 'to' => '2026-09-30',
        ]));

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('ORD-001', $response->json('data.0.number'));
    }

    public function test_shipments_can_be_filtered_by_customer_status_and_etd_range(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('shipments.datatable', ['customer_id' => $this->first->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('SHP-001', $response->json('data.0.number'));

        $response = $this->actingAs($this->user)->getJson(route('shipments.datatable', [
            'status' => 'in_transit', 'from' => '2026-09-01', 'to' => '2026-09-30',
        ]));

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('SHP-001', $response->json('data.0.number'));

        // The preparing shipment has no ETD and must not match an ETD range.
        $response = $this->actingAs($this->user)->getJson(route('shipments.datatable', [
            'from' => '2026-01-01', 'to' => '2026-12-31',
        ]));

        $this->assertCount(1, $response->json('data'));
    }

    public function test_quotes_can_be_filtered_by_customer_status_and_date_range(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('quotes.datatable', ['customer_id' => $this->second->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('QT-002', $response->json('data.0.number'));

        $response = $this->actingAs($this->user)->getJson(route('quotes.datatable', [
            'status' => 'sent', 'from' => '2026-09-01', 'to' => '2026-09-30',
        ]));

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('QT-001', $response->json('data.0.number'));
    }
}
