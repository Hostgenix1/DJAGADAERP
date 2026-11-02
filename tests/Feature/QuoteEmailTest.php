<?php

namespace Tests\Feature;

use App\Mail\QuoteMailable;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class QuoteEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_quotation_can_be_emailed_with_its_pdf_attached(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        Permission::create(['name' => 'update-quotes']);
        $user->givePermissionTo('update-quotes');

        $customer = Customer::create([
            'company_name' => 'Client LLC',
            'email' => 'client@example.com',
        ]);

        $quote = Quote::create([
            'number' => 'QT/2026/00001',
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'valid_until' => now()->addDays(14)->toDateString(),
            'status' => 'sent',
            'revision' => 1,
            'vat_mode' => 'excluded',
            'vat_rate' => 5,
            'subtotal' => 100,
            'tax_amount' => 5,
            'total' => 105,
        ]);

        $quote->items()->create([
            'description' => 'Widget',
            'qty' => 1,
            'unit' => 'pc',
            'unit_price' => 100,
            'tax_rate' => 5,
            'discount_pct' => 0,
            'line_total' => 100,
        ]);

        $response = $this->actingAs($user)->post(route('quotes.email', $quote), [
            'email' => 'client@example.com',
            'message' => 'Please review the attached quotation.',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        Mail::assertSent(QuoteMailable::class, function (QuoteMailable $mail) use ($quote) {
            return $mail->hasTo('client@example.com')
                && $mail->quote->is($quote)
                && $mail->pdfContent !== '';
        });
    }

    public function test_an_invalid_recipient_is_rejected(): void
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'update-quotes']);
        $user->givePermissionTo('update-quotes');

        $customer = Customer::create(['company_name' => 'Client LLC']);
        $quote = Quote::create([
            'number' => 'QT/2026/00002',
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'status' => 'draft',
            'revision' => 1,
            'vat_mode' => 'none',
        ]);

        $this->actingAs($user)
            ->post(route('quotes.email', $quote), ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');
    }
}
