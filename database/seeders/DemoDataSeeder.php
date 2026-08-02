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
        $aedCurrency = Currency::where('code', 'AED')->first();

        $brandA = Brand::firstOrCreate(['name' => 'Samsung']);
        $brandB = Brand::firstOrCreate(['name' => 'HP']);
        $brandC = Brand::firstOrCreate(['name' => 'Canon']);

        $catElectronics = ProductCategory::firstOrCreate(['name' => 'Electronics']);
        $catOffice = ProductCategory::firstOrCreate(['name' => 'Office Supplies']);
        $catPrinting = ProductCategory::firstOrCreate(['name' => 'Printing Solutions']);

        $supplierA = Supplier::firstOrCreate(['company_name' => 'Gulf Trading FZE'], ['email' => 'sales@gulftrading.ae', 'phone' => '+971 4 555 0100', 'is_active' => true]);
        $supplierB = Supplier::firstOrCreate(['company_name' => 'Al Futtaim Trading'], ['email' => 'info@alfuttaim.ae', 'phone' => '+971 4 555 0200', 'is_active' => true]);

        $products = [];
        $productDefs = [
            ['name' => 'Samsung 24" Monitor', 'sku' => 'SAM-M24', 'brand_id' => $brandA->id, 'category_id' => $catElectronics->id, 'supplier_id' => $supplierA->id, 'buy_price' => 450.00, 'sell_price' => 749.00, 'pack_qty' => 1, 'pack_type' => 'unit'],
            ['name' => 'HP LaserJet Pro Printer', 'sku' => 'HP-LJ400', 'brand_id' => $brandB->id, 'category_id' => $catPrinting->id, 'supplier_id' => $supplierA->id, 'buy_price' => 899.00, 'sell_price' => 1499.00, 'pack_qty' => 1, 'pack_type' => 'unit'],
            ['name' => 'Canon Ink Cartridge Pack', 'sku' => 'CAN-IC10', 'brand_id' => $brandC->id, 'category_id' => $catPrinting->id, 'supplier_id' => $supplierB->id, 'buy_price' => 85.00, 'sell_price' => 149.00, 'pack_qty' => 10, 'pack_type' => 'box'],
            ['name' => 'A4 Copy Paper (Ream)', 'sku' => 'OFF-A4R', 'brand_id' => $brandC->id, 'category_id' => $catOffice->id, 'supplier_id' => $supplierB->id, 'buy_price' => 12.00, 'sell_price' => 22.00, 'pack_qty' => 5, 'pack_type' => 'carton'],
            ['name' => 'Ergonomic Office Chair', 'sku' => 'OFF-CHAIR', 'brand_id' => $brandB->id, 'category_id' => $catOffice->id, 'supplier_id' => $supplierB->id, 'buy_price' => 450.00, 'sell_price' => 899.00, 'pack_qty' => 1, 'pack_type' => 'unit'],
        ];
        foreach ($productDefs as $p) {
            $products[] = Product::firstOrCreate(['sku' => $p['sku']], $p);
        }

        $customerData = [
            ['company_name' => 'Emirates Trading LLC', 'email' => 'procurement@emiratestrading.ae', 'phone' => '+971 4 333 1001', 'city' => 'Dubai', 'country' => 'UAE'],
            ['company_name' => 'Abu Dhabi Construction Co', 'email' => 'purchase@adcc.ae', 'phone' => '+971 2 444 1002', 'city' => 'Abu Dhabi', 'country' => 'UAE'],
            ['company_name' => 'Sharjah Medical Supplies', 'email' => 'info@sharjahmed.ae', 'phone' => '+971 6 555 1003', 'city' => 'Sharjah', 'country' => 'UAE'],
            ['company_name' => 'Ajman Electronics FZE', 'email' => 'sales@ajmanelec.ae', 'phone' => '+971 6 745 1004', 'city' => 'Ajman', 'country' => 'UAE'],
            ['company_name' => 'RAK Industries LLC', 'email' => 'ops@rakindustries.ae', 'phone' => '+971 7 244 1005', 'city' => 'Ras Al Khaimah', 'country' => 'UAE'],
            ['company_name' => 'Fujairah Exports Co', 'email' => 'exports@fujairahexp.ae', 'phone' => '+971 9 222 1006', 'city' => 'Fujairah', 'country' => 'UAE'],
            ['company_name' => 'Al Ain Services Group', 'email' => 'hello@alainservices.ae', 'phone' => '+971 3 765 1007', 'city' => 'Al Ain', 'country' => 'UAE'],
            ['company_name' => 'Gulf Star Import Export', 'email' => 'trade@gulfstar.ae', 'phone' => '+971 4 888 1008', 'city' => 'Dubai', 'country' => 'UAE'],
        ];

        $customers = [];
        foreach ($customerData as $c) {
            $customers[] = Customer::firstOrCreate(['company_name' => $c['company_name']], $c + ['currency_id' => $aedCurrency->id, 'is_active' => true]);
        }

        foreach ($customers as $i => $cust) {
            $numContacts = rand(1, 3);
            for ($j = 0; $j < $numContacts; $j++) {
                Contact::firstOrCreate(
                    ['customer_id' => $cust->id, 'full_name' => 'Contact '.$j],
                    ['email' => 'contact'.$j.'@'.str_replace(' ', '', strtolower($cust->company_name)).'.ae', 'phone' => '+971 '.rand(2, 9).' '.rand(100, 999).' '.rand(1000, 9999), 'position' => ['Manager', 'Procurement', 'Director'][rand(0, 2)]]
                );
            }
        }

        $leadSources = ['website', 'referral', 'cold_call', 'trade_show', 'social_media', 'marketing'];
        $leadStatuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
        $leadCompanies = [
            ['company_name' => 'Dubai Mall Retail', 'contact_name' => 'Ahmed Al Maktoum', 'email' => 'ahmed@dubaimallretail.ae'],
            ['company_name' => 'Jebel Ali Free Zone Authority', 'contact_name' => 'Sarah Johnson', 'email' => 'sarah@jafza.ae'],
            ['company_name' => 'Khalifa Industrial Zone', 'contact_name' => 'Mohammed Al Hashimi', 'email' => 'mohammed@kizad.ae'],
            ['company_name' => 'Masdar Clean Energy', 'contact_name' => 'Fatima Al Suwaidi', 'email' => 'fatima@masdar.ae'],
            ['company_name' => 'Etihad Airways Cargo', 'contact_name' => 'James Wilson', 'email' => 'james@etihadcargo.ae'],
            ['company_name' => 'DP World Logistics', 'contact_name' => 'Omar Al Balushi', 'email' => 'omar@dpworld.ae'],
        ];

        foreach ($leadCompanies as $ld) {
            Lead::firstOrCreate(
                ['company_name' => $ld['company_name']],
                $ld + [
                    'source' => $leadSources[array_rand($leadSources)],
                    'status' => $leadStatuses[array_rand($leadStatuses)],
                    'expected_amount' => rand(500, 25000),
                    'currency_id' => $aedCurrency->id,
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
                    'tax_rate' => 5,
                    'discount_pct' => 0,
                    'line_total' => $line,
                ]);
                $sub += $line;
            }
            $tax = round($sub * 0.05, 2);
            $q->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $sub + $tax]);
        }

        $invStatuses = ['paid', 'paid', 'paid', 'paid', 'paid', 'partial', 'partial', 'sent'];
        $types = ['commercial', 'proforma'];
        for ($i = 0; $i < 8; $i++) {
            $cust = $customers[array_rand($customers)];
            $status = $invStatuses[$i];
            $type = $types[array_rand($types)];
            $monthsAgo = rand(0, 11);
            $inv = Invoice::create([
                'number' => Invoice::nextNumber($type),
                'type' => $type,
                'customer_id' => $cust->id,
                'currency_id' => $aedCurrency->id,
                'invoice_date' => now()->subMonths($monthsAgo)->subDays(rand(0, 20)),
                'due_date' => now()->subMonths($monthsAgo)->addDays(rand(5, 30)),
                'status' => $status,
                'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0, 'paid_amount' => 0,
            ]);
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
                    'tax_rate' => 5,
                    'discount_pct' => 0,
                    'line_total' => $line,
                ]);
                $sub += $line;
            }
            $tax = round($sub * 0.05, 2);
            $total = $sub + $tax;
            if ($status === 'paid') {
                $inv->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $total, 'paid_amount' => $total, 'status' => 'paid']);
            } elseif ($status === 'partial') {
                $paidAmt = round($total * rand(30, 70) / 100, 2);
                $inv->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $total, 'paid_amount' => $paidAmt, 'status' => 'partial']);
            } else {
                $inv->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $total, 'paid_amount' => 0, 'status' => $status]);
            }
        }

        $paidInvoices = Invoice::where('paid_amount', '>', 0)->get();
        foreach ($paidInvoices as $inv) {
            $cust = $inv->customer;
            if (!$cust) continue;
            $pType = ['cash', 'bank', 'transfer', 'cheque', 'mobile'][array_rand(['cash', 'bank', 'transfer', 'cheque', 'mobile'])];
            Payment::create([
                'number' => Payment::nextNumber(),
                'type' => 'customer',
                'customer_id' => $cust->id,
                'currency_id' => $aedCurrency->id,
                'method' => $pType,
                'amount' => $inv->paid_amount,
                'rate' => 1,
                'paid_on' => $inv->invoice_date->addDays(rand(1, 15)),
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
                'type' => ['call', 'email', 'meeting', 'task'][rand(0, 3)],
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
