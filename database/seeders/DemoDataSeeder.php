<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Contact;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\FollowUp;
use App\Models\Communication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $defaultCurrency = Currency::firstOrCreate(['code' => 'USD'], ['name' => 'US Dollar', 'symbol' => '$', 'rate' => 1]);
        $eurCurrency = Currency::firstOrCreate(['code' => 'EUR'], ['name' => 'Euro', 'symbol' => '€', 'rate' => 1.09]);
        $xcdCurrency = Currency::firstOrCreate(['code' => 'XCD'], ['name' => 'East Caribbean Dollar', 'symbol' => 'EC$', 'rate' => 2.70]);

        $brandA = Brand::firstOrCreate(['name' => 'Brand Alpha']);
        $brandB = Brand::firstOrCreate(['name' => 'Brand Beta']);
        $brandC = Brand::firstOrCreate(['name' => 'Brand Gamma']);

        $catElectronics = ProductCategory::firstOrCreate(['name' => 'Electronics']);
        $catOffice = ProductCategory::firstOrCreate(['name' => 'Office Supplies']);
        $catFurniture = ProductCategory::firstOrCreate(['name' => 'Furniture']);

        $supplierA = Supplier::firstOrCreate(['company_name' => 'TechParts Ltd'], ['email' => 'sales@techparts.com', 'phone' => '555-0100', 'is_active' => true]);
        $supplierB = Supplier::firstOrCreate(['company_name' => 'GlobalGoods Inc'], ['email' => 'info@globalgoods.com', 'phone' => '555-0200', 'is_active' => true]);

        $products = [];
        $productDefs = [
            ['name' => 'Wireless Mouse', 'sku' => 'WM-001', 'brand_id' => $brandA->id, 'category_id' => $catElectronics->id, 'supplier_id' => $supplierA->id, 'buy_price' => 8.50, 'sell_price' => 19.99, 'pack_qty' => 100, 'pack_type' => 'box'],
            ['name' => 'Mechanical Keyboard', 'sku' => 'MK-002', 'brand_id' => $brandA->id, 'category_id' => $catElectronics->id, 'supplier_id' => $supplierA->id, 'buy_price' => 25.00, 'sell_price' => 69.99, 'pack_qty' => 50, 'pack_type' => 'box'],
            ['name' => 'USB-C Hub 7-in-1', 'sku' => 'UH-003', 'brand_id' => $brandB->id, 'category_id' => $catElectronics->id, 'supplier_id' => $supplierA->id, 'buy_price' => 12.00, 'sell_price' => 34.99, 'pack_qty' => 80, 'pack_type' => 'box'],
            ['name' => 'A4 Copy Paper (500 sheets)', 'sku' => 'CP-004', 'brand_id' => $brandC->id, 'category_id' => $catOffice->id, 'supplier_id' => $supplierB->id, 'buy_price' => 3.20, 'sell_price' => 6.99, 'pack_qty' => 10, 'pack_type' => 'carton'],
            ['name' => 'Ergonomic Office Chair', 'sku' => 'EC-005', 'brand_id' => $brandC->id, 'category_id' => $catFurniture->id, 'supplier_id' => $supplierB->id, 'buy_price' => 120.00, 'sell_price' => 299.99, 'pack_qty' => 1, 'pack_type' => 'unit'],
        ];
        foreach ($productDefs as $p) {
            $products[] = Product::firstOrCreate(['sku' => $p['sku']], $p);
        }

        $customerData = [
            ['company_name' => 'Island Resort Hotels', 'email' => 'procurement@islandresort.com', 'phone' => '555-1001', 'city' => 'St. John\'s', 'country' => 'Antigua'],
            ['company_name' => 'Caribbean Logistics Co', 'email' => 'ops@cariblogistics.com', 'phone' => '555-1002', 'city' => 'Port of Spain', 'country' => 'Trinidad'],
            ['company_name' => 'Sunset Medical Clinic', 'email' => 'admin@sunsetmed.com', 'phone' => '555-1003', 'city' => 'Bridgetown', 'country' => 'Barbados'],
            ['company_name' => 'Eastern Star Trading', 'email' => 'trading@easternstar.com', 'phone' => '555-1004', 'city' => 'Roseau', 'country' => 'Dominica'],
            ['company_name' => 'BlueWave Technologies', 'email' => 'hello@bluewave.tech', 'phone' => '555-1005', 'city' => 'Kingstown', 'country' => 'St. Vincent'],
            ['company_name' => 'Grand Palm Suites', 'email' => 'purchase@grandpalm.com', 'phone' => '555-1006', 'city' => 'Castries', 'country' => 'St. Lucia'],
            ['company_name' => 'NovaTech Solutions', 'email' => 'info@novatech.com', 'phone' => '555-1007', 'city' => 'St. George\'s', 'country' => 'Grenada'],
            ['company_name' => 'Royal Export Group', 'email' => 'exports@royalexp.com', 'phone' => '555-1008', 'city' => 'San Juan', 'country' => 'Puerto Rico'],
        ];

        $customers = [];
        foreach ($customerData as $c) {
            $customers[] = Customer::firstOrCreate(['company_name' => $c['company_name']], $c + ['currency_id' => $defaultCurrency->id, 'is_active' => true]);
        }

        foreach ($customers as $i => $cust) {
            $numContacts = rand(1, 3);
            for ($j = 0; $j < $numContacts; $j++) {
                Contact::firstOrCreate(
                    ['customer_id' => $cust->id, 'full_name' => 'Contact '.$j],
                    ['email' => 'contact'.$j.'@'.str_replace(' ', '', strtolower($cust->company_name)).'.com', 'phone' => '555-'.rand(2000, 9999), 'position' => ['Manager', 'Procurement', 'Director'][rand(0, 2)]]
                );
            }
        }

        $leadSources = ['website', 'referral', 'cold_call', 'trade_show', 'social_media', 'marketing'];
        $leadStatuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
        $leadCompanies = [
            ['company_name' => 'Sunshine Distributors', 'contact_name' => 'Mark Johnson', 'email' => 'mark@sunshine.com'],
            ['company_name' => 'Peak Performance Gym', 'contact_name' => 'Lisa Chen', 'email' => 'lisa@peakgym.com'],
            ['company_name' => 'Harbour View Restaurant', 'contact_name' => 'Carlos Diaz', 'email' => 'carlos@harbour.com'],
            ['company_name' => 'Green Valley Schools', 'contact_name' => 'Nina Patel', 'email' => 'nina@greenvalley.edu'],
            ['company_name' => 'Metro Express Delivery', 'contact_name' => 'Andre Williams', 'email' => 'andre@metroexp.com'],
            ['company_name' => 'Crystal Clear Pools', 'contact_name' => 'Sophie Martin', 'email' => 'sophie@crystalpools.com'],
        ];

        foreach ($leadCompanies as $ld) {
            Lead::firstOrCreate(
                ['company_name' => $ld['company_name']],
                $ld + [
                    'source' => $leadSources[array_rand($leadSources)],
                    'status' => $leadStatuses[array_rand($leadStatuses)],
                    'expected_amount' => rand(500, 25000),
                    'currency_id' => $defaultCurrency->id,
                    'expected_date' => now()->addDays(rand(7, 90)),
                ]
            );
        }

        $statuses = ['draft', 'sent', 'accepted', 'rejected', 'expired'];
        for ($i = 0; $i < 6; $i++) {
            $cust = $customers[array_rand($customers)];
            $q = Quote::firstOrCreate(
                ['number' => Quote::nextNumber()],
                [
                    'customer_id' => $cust->id,
                    'currency_id' => $cust->currency_id,
                    'date' => now()->subDays(rand(0, 60)),
                    'valid_until' => now()->subDays(rand(0, 30))->addDays(30),
                    'status' => $statuses[array_rand($statuses)],
                    'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0,
                ]
            );
            $numItems = rand(1, 4);
            $sub = 0;
            for ($j = 0; $j < $numItems; $j++) {
                $prod = $products[array_rand($products)];
                $qty = rand(1, 20);
                $line = $qty * $prod->sell_price;
                QuoteItem::create([
                    'quote_id' => $q->id,
                    'product_id' => $prod->id,
                    'description' => $prod->name,
                    'qty' => $qty,
                    'unit' => 'pc',
                    'unit_price' => $prod->sell_price,
                    'tax_rate' => 0,
                    'discount_pct' => 0,
                    'line_total' => $line,
                ]);
                $sub += $line;
            }
            $q->update(['subtotal' => $sub, 'total' => $sub]);
        }

        $invStatuses = ['draft', 'sent', 'partial', 'paid'];
        $types = ['commercial', 'proforma'];
        for ($i = 0; $i < 8; $i++) {
            $cust = $customers[array_rand($customers)];
            $inv = Invoice::firstOrCreate(
                ['number' => Invoice::nextNumber()],
                [
                    'type' => $types[array_rand($types)],
                    'customer_id' => $cust->id,
                    'currency_id' => $cust->currency_id,
                    'invoice_date' => now()->subDays(rand(0, 90)),
                    'due_date' => now()->subDays(rand(0, 30)),
                    'status' => $invStatuses[array_rand($invStatuses)],
                    'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0, 'paid_amount' => 0,
                ]
            );
            $numItems = rand(1, 5);
            $sub = 0;
            for ($j = 0; $j < $numItems; $j++) {
                $prod = $products[array_rand($products)];
                $qty = rand(1, 30);
                $line = $qty * $prod->sell_price;
                InvoiceItem::create([
                    'invoice_id' => $inv->id,
                    'product_id' => $prod->id,
                    'description' => $prod->name,
                    'qty' => $qty,
                    'unit' => 'pc',
                    'unit_price' => $prod->sell_price,
                    'tax_rate' => 0,
                    'discount_pct' => 0,
                    'line_total' => $line,
                ]);
                $sub += $line;
            }
            $paid = in_array($inv->status, ['paid']) ? $sub : (in_array($inv->status, ['partial']) ? round($sub * rand(30, 70) / 100, 2) : 0);
            $inv->update(['subtotal' => $sub, 'total' => $sub, 'paid_amount' => $paid, 'status' => $paid >= $sub ? 'paid' : $inv->status]);
        }

        for ($i = 0; $i < 10; $i++) {
            $cust = $customers[array_rand($customers)];
            $paidInvs = $cust->invoices()->where('status', 'paid')->get();
            if ($paidInvs->isEmpty()) continue;
            $inv = $paidInvs->random();
            $pType = ['cash', 'bank', 'transfer', 'cheque', 'mobile'][array_rand(['cash', 'bank', 'transfer', 'cheque', 'mobile'])];
            Payment::create([
                'number' => Payment::nextNumber(),
                'type' => 'customer',
                'customer_id' => $cust->id,
                'currency_id' => $cust->currency_id,
                'method' => $pType,
                'amount' => $inv->paid_amount,
                'rate' => 1,
                'paid_on' => $inv->invoice_date,
                'reference' => strtoupper(uniqid('PAY')),
                'notes' => 'Payment for '.$inv->number,
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            $cust = $customers[array_rand($customers)];
            Communication::create([
                'communicable_type' => Customer::class,
                'communicable_id' => $cust->id,
                'type' => ['call', 'whatsapp', 'email', 'meeting', 'note'][rand(0, 4)],
                'direction' => ['outbound', 'inbound'][rand(0, 1)],
                'subject' => 'Demo communication',
                'body' => 'Sample communication log for demo purposes.',
                'user_id' => 1,
                'contact_id' => Contact::where('customer_id', $cust->id)->first()?->id,
                'occurred_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        for ($i = 0; $i < 8; $i++) {
            $cust = $customers[array_rand($customers)];
            FollowUp::create([
                'followable_type' => Customer::class,
                'followable_id' => $cust->id,
                'type' => ['call', 'email', 'visit', 'meeting'][rand(0, 3)],
                'due_date' => now()->addDays(rand(-5, 15)),
                'note' => 'Follow up on order status',
                'assigned_to' => 1,
            ]);
        }

        $this->command->info('Demo data seeded: '.
            count($customers).' customers, '.
            count($products).' products, '.
            Lead::count().' leads, '.
            Quote::count().' quotes, '.
            Invoice::count().' invoices, '.
            Payment::count().' payments.');
    }
}
