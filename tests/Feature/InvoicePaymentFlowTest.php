<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_totals_and_items_are_saved_together(): void
    {
        $invoice = app(InvoiceService::class)->createWithItems(
            $this->invoiceData($this->customer()),
            [$this->item(2, 100)],
        );

        $this->assertSame(200.0, (float) $invoice->subtotal);
        $this->assertSame(10.0, (float) $invoice->tax_amount);
        $this->assertSame(210.0, (float) $invoice->total);
        $this->assertCount(1, $invoice->items);
        $this->assertSame(200.0, (float) $invoice->items->first()->line_total);
    }

    public function test_updating_an_invoice_replaces_items_and_recalculates(): void
    {
        $service = app(InvoiceService::class);
        $invoice = $service->createWithItems($this->invoiceData($this->customer()), [$this->item(2, 100)]);

        $updated = $service->updateWithItems(
            $invoice,
            $this->invoiceData($invoice->customer, vatMode: 'none', vatRate: 0),
            [$this->item(3, 50, 'Replacement')],
        );

        $this->assertSame(150.0, (float) $updated->total);
        $this->assertCount(1, $updated->items()->get());
        $this->assertSame('Replacement', $updated->items()->first()->description);
    }

    public function test_payment_allocation_updates_the_invoice_balance(): void
    {
        $customer = $this->customer();
        $invoice = app(InvoiceService::class)->createWithItems(
            $this->invoiceData($customer),
            [$this->item(2, 100)],
        );

        $payment = app(PaymentService::class)->createWithAllocation([
            'type' => 'customer',
            'customer_id' => $customer->id,
            'method' => 'bank',
            'amount' => 210,
            'rate' => 1,
            'paid_on' => now()->toDateString(),
        ], [
            ['invoice_id' => $invoice->id, 'amount' => 210],
        ]);

        $invoice->refresh();

        $this->assertSame(210.0, (float) $invoice->paid_amount);
        $this->assertSame('paid', $invoice->status);
        $this->assertSame(0.0, $invoice->balance);
        $this->assertSame(1, $payment->invoices()->count());
    }

    private function customer(): Customer
    {
        return Customer::create(['company_name' => 'Test Trading LLC']);
    }

    private function invoiceData(Customer $customer, string $vatMode = 'excluded', float $vatRate = 5): array
    {
        return [
            'type' => 'commercial',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'sent',
            'vat_mode' => $vatMode,
            'vat_rate' => $vatRate,
            'discount' => 0,
        ];
    }

    private function item(int $qty, float $unitPrice, string $description = 'Widget'): array
    {
        return [
            'description' => $description,
            'qty' => $qty,
            'unit_price' => $unitPrice,
            'tax_rate' => null,
            'discount_pct' => 0,
        ];
    }
}
