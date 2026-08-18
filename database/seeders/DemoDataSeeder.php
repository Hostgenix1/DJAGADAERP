<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Communication;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\FollowUp;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Supplier;
use App\Models\SupplierBill;
use App\Models\SupplierBillItem;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    private array $paymentTerms = [
        'Due on Receipt',
        'Net 15',
        'Net 30',
        'Net 45',
        'Net 60',
        '30% advance + 70% before shipment',
        'Balance due according to agreed terms',
    ];

    private array $incoterms = ['EXW', 'FOB', 'CFR', 'CIF', 'DAP', 'DDP', 'FCA', 'CPT', 'CIP'];

    public function run(): void
    {
        $this->clean();

        $this->seedPaymentTerms();

        $aed = Currency::where('code', 'AED')->first();

        // ----- Brands / Categories -----
        $brandA = Brand::create(['name' => 'Samsung']);
        $brandB = Brand::create(['name' => 'HP']);
        $brandC = Brand::create(['name' => 'Canon']);

        $catElectronics = ProductCategory::create(['name' => 'Electronics']);
        $catOffice = ProductCategory::create(['name' => 'Office Supplies']);
        $catPrinting = ProductCategory::create(['name' => 'Printing Solutions']);

        // ----- Suppliers -----
        $supplierA = Supplier::create([
            'company_name' => 'Gulf Trading FZE', 'email' => 'sales@gulftrading.ae',
            'phone' => '+971 4 555 0100', 'city' => 'Dubai', 'country' => 'UAE',
            'currency_id' => $aed->id, 'payment_terms' => 'Net 30', 'is_active' => true,
        ]);
        $supplierB = Supplier::create([
            'company_name' => 'Al Futtaim Trading', 'email' => 'info@alfuttaim.ae',
            'phone' => '+971 4 555 0200', 'city' => 'Dubai', 'country' => 'UAE',
            'currency_id' => $aed->id, 'payment_terms' => '30% advance + 70% before shipment', 'is_active' => true,
        ]);

        // ----- Products -----
        $products = [];
        $productDefs = [
            ['name' => 'Samsung 24-inch Monitor', 'sku' => 'SAM-M24', 'brand_id' => $brandA->id, 'category_id' => $catElectronics->id, 'supplier_id' => $supplierA->id, 'buy_price' => 450.00, 'sell_price' => 749.00, 'unit' => 'Unit'],
            ['name' => 'HP LaserJet Pro Printer', 'sku' => 'HP-LJ400', 'brand_id' => $brandB->id, 'category_id' => $catPrinting->id, 'supplier_id' => $supplierA->id, 'buy_price' => 899.00, 'sell_price' => 1499.00, 'unit' => 'Unit'],
            ['name' => 'Canon Ink Cartridge Pack', 'sku' => 'CAN-IC10', 'brand_id' => $brandC->id, 'category_id' => $catPrinting->id, 'supplier_id' => $supplierB->id, 'buy_price' => 85.00, 'sell_price' => 149.00, 'unit' => 'Box'],
            ['name' => 'A4 Copy Paper (Ream)', 'sku' => 'OFF-A4R', 'brand_id' => $brandC->id, 'category_id' => $catOffice->id, 'supplier_id' => $supplierB->id, 'buy_price' => 12.00, 'sell_price' => 22.00, 'unit' => 'Carton'],
            ['name' => 'Ergonomic Office Chair', 'sku' => 'OFF-CHAIR', 'brand_id' => $brandB->id, 'category_id' => $catOffice->id, 'supplier_id' => $supplierB->id, 'buy_price' => 450.00, 'sell_price' => 899.00, 'unit' => 'Unit'],
        ];
        foreach ($productDefs as $p) {
            $products[] = Product::create($p);
        }

        // ----- Customers + Contacts -----
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
        foreach ($customerData as $i => $c) {
            $customers[] = Customer::create($c + ['currency_id' => $aed->id, 'is_active' => true]);
            $numContacts = rand(1, 2);
            for ($j = 0; $j < $numContacts; $j++) {
                Contact::create([
                    'customer_id' => $customers[$i]->id,
                    'full_name' => ['Ahmed', 'Sarah', 'Mohammed', 'Fatima', 'James'][rand(0, 4)].' '.['Ali', 'Khan', 'Hassan', 'Noah', 'Omar'][rand(0, 4)],
                    'email' => 'contact'.$j.'@'.str_replace(' ', '', strtolower($c['company_name'])).'.ae',
                    'phone' => '+971 '.rand(2, 9).' '.rand(100, 999).' '.rand(1000, 9999),
                    'position' => ['Manager', 'Procurement', 'Director'][rand(0, 2)],
                    'is_primary' => $j === 0,
                ]);
            }
        }

        // ----- Leads -----
        $leadCompanies = [
            ['company_name' => 'Dubai Mall Retail', 'contact_name' => 'Ahmed Al Maktoum', 'email' => 'ahmed@dubaimallretail.ae'],
            ['company_name' => 'Jebel Ali Free Zone Authority', 'contact_name' => 'Sarah Johnson', 'email' => 'sarah@jafza.ae'],
            ['company_name' => 'Khalifa Industrial Zone', 'contact_name' => 'Mohammed Al Hashimi', 'email' => 'mohammed@kizad.ae'],
            ['company_name' => 'Masdar Clean Energy', 'contact_name' => 'Fatima Al Suwaidi', 'email' => 'fatima@masdar.ae'],
            ['company_name' => 'Etihad Airways Cargo', 'contact_name' => 'James Wilson', 'email' => 'james@etihadcargo.ae'],
            ['company_name' => 'DP World Logistics', 'contact_name' => 'Omar Al Balushi', 'email' => 'omar@dpworld.ae'],
        ];
        foreach ($leadCompanies as $ld) {
            Lead::create($ld + [
                'source' => ['website', 'referral', 'cold_call', 'trade_show', 'social_media', 'marketing'][rand(0, 5)],
                'status' => ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'][rand(0, 5)],
                'expected_amount' => rand(500, 25000),
                'currency_id' => $aed->id,
                'expected_date' => now()->addDays(rand(7, 90)),
            ]);
        }

        // ----- Quotes -----
        $quoteStatuses = ['draft', 'sent', 'accepted', 'rejected', 'expired'];
        for ($i = 0; $i < 6; $i++) {
            $cust = $customers[array_rand($customers)];
            $q = Quote::create([
                'number' => Quote::nextNumber(),
                'customer_id' => $cust->id,
                'currency_id' => $cust->currency_id,
                'date' => now()->subDays(rand(0, 60)),
                'valid_until' => now()->addDays(rand(15, 45)),
                'status' => $quoteStatuses[array_rand($quoteStatuses)],
                'payment_terms' => $this->paymentTerms[array_rand($this->paymentTerms)],
                'delivery_terms' => $this->incoterms[array_rand($this->incoterms)],
                'offer_valid' => rand(7, 30),
                'vat_mode' => 'excluded',
                'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0,
            ]);
            [$sub, $tax] = $this->addItems(QuoteItem::class, 'quote_id', $q->id, $products);
            $q->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $sub + $tax]);
        }

        // ----- Invoices -----
        $invStatuses = ['paid', 'paid', 'paid', 'partial', 'partial', 'sent', 'sent', 'draft'];
        $types = ['commercial', 'commercial', 'commercial', 'commercial', 'proforma', 'proforma', 'commercial', 'commercial'];
        for ($i = 0; $i < 8; $i++) {
            $cust = $customers[array_rand($customers)];
            $status = $invStatuses[$i];
            $type = $types[$i];
            $monthsAgo = rand(0, 3);
            $invoiceDate = now()->subMonths($monthsAgo)->subDays(rand(0, 15));
            $inv = Invoice::create([
                'number' => Invoice::nextNumber($type),
                'type' => $type,
                'customer_id' => $cust->id,
                'currency_id' => $aed->id,
                'invoice_date' => $invoiceDate,
                'due_date' => $invoiceDate->copy()->addDays([15, 30, 45][rand(0, 2)]),
                'status' => $status === 'paid' ? 'sent' : $status,
                'reference_no' => 'REF-'.rand(1000, 9999),
                'payment_terms' => $this->paymentTerms[array_rand($this->paymentTerms)],
                'delivery_terms' => $this->incoterms[array_rand($this->incoterms)],
                'port_of_loading' => 'Jebel Ali Port, Dubai',
                'port_of_discharge' => 'Port of Dakar, Senegal',
                'goods_origin' => 'UAE',
                'offer_valid' => 15,
                'vat_mode' => 'excluded',
                'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0, 'paid_amount' => 0,
            ]);
            [$sub, $tax] = $this->addItems(InvoiceItem::class, 'invoice_id', $inv->id, $products);
            $total = $sub + $tax;
            $inv->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $total]);

            if ($status === 'paid') {
                $this->recordPayment('customer', ['customer_id' => $cust->id], $total, $inv->id, $invoiceDate);
            } elseif ($status === 'partial') {
                $this->recordPayment('customer', ['customer_id' => $cust->id], round($total * rand(30, 60) / 100, 2), $inv->id, $invoiceDate);
            }
        }

        // ----- Purchase Orders -----
        $poStatuses = ['draft', 'confirmed', 'billed', 'billed'];
        for ($i = 0; $i < 4; $i++) {
            $supplier = [$supplierA, $supplierB][rand(0, 1)];
            $po = PurchaseOrder::create([
                'number' => PurchaseOrder::nextNumber(),
                'supplier_id' => $supplier->id,
                'currency_id' => $aed->id,
                'po_date' => now()->subDays(rand(0, 45)),
                'expected_delivery' => now()->addDays(rand(7, 60)),
                'status' => $poStatuses[$i],
                'payment_terms' => $supplier->payment_terms,
                'delivery_terms' => $this->incoterms[array_rand($this->incoterms)],
                'port_of_loading' => 'Port of Shanghai, China',
                'port_of_discharge' => 'Jebel Ali Port, Dubai',
                'goods_origin' => 'China',
                'vat_mode' => 'excluded',
                'subtotal' => 0, 'tax_amount' => 0, 'discount' => 0, 'total' => 0,
            ]);
            [$sub, $tax] = $this->addItems(PurchaseOrderItem::class, 'purchase_order_id', $po->id, $products, true);
            $po->update(['subtotal' => $sub, 'tax_amount' => $tax, 'total' => $sub + $tax]);

            if ($poStatuses[$i] === 'billed') {
                // ----- Supplier Bill from PO -----
                $bill = SupplierBill::create([
                    'number' => SupplierBill::nextNumber(),
                    'supplier_id' => $supplier->id,
                    'purchase_order_id' => $po->id,
                    'currency_id' => $aed->id,
                    'bill_date' => $po->po_date->addDays(rand(3, 10)),
                    'due_date' => $po->po_date->addDays(rand(20, 45)),
                    'status' => 'confirmed',
                    'payment_terms' => $supplier->payment_terms,
                    'reference_no' => $supplier->company_name.' INV #'.rand(1000, 9999),
                    'vat_mode' => 'excluded',
                    'subtotal' => $sub, 'tax_amount' => $tax, 'discount' => 0, 'total' => $sub + $tax, 'paid_amount' => 0,
                ]);
                $this->copyItems($po, $bill);
            }
        }

        // ----- Supplier Bill payments -----
        $bills = SupplierBill::where('status', 'confirmed')->get();
        $paidCount = 0;
        foreach ($bills as $bill) {
            if ($paidCount === 0) {
                $this->recordPayment('supplier', ['supplier_id' => $bill->supplier_id], $bill->total, null, $bill->bill_date, $bill->id);
                $paidCount++;
            } elseif ($paidCount === 1) {
                $this->recordPayment('supplier', ['supplier_id' => $bill->supplier_id], round($bill->total * 0.4, 2), null, $bill->bill_date, $bill->id);
                $paidCount++;
            }
        }

        // ----- Communications / Follow-ups -----
        for ($i = 0; $i < 8; $i++) {
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

        for ($i = 0; $i < 6; $i++) {
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

        $this->command->info('Demo data seeded: '.count($customers).' customers, '.count($products).' products, '
            .Lead::count().' leads, '.Quote::count().' quotes, '.Invoice::count().' invoices, '
            .Payment::count().' payments, '.PurchaseOrder::count().' POs, '.SupplierBill::count().' bills.');
    }

    private function clean(): void
    {
        DB::table('supplier_bill_payments')->delete();
        DB::table('invoice_payments')->delete();
        DB::table('payments')->delete();
        DB::table('supplier_bill_items')->delete();
        DB::table('supplier_bills')->delete();
        DB::table('purchase_order_items')->delete();
        DB::table('purchase_orders')->delete();
        DB::table('invoice_items')->delete();
        DB::table('invoices')->delete();
        DB::table('quote_items')->delete();
        DB::table('quotes')->delete();
        DB::table('leads')->delete();
        DB::table('communications')->delete();
        DB::table('follow_ups')->delete();
        DB::table('contacts')->delete();
        DB::table('customers')->delete();
        DB::table('suppliers')->delete();
        DB::table('products')->delete();
        DB::table('brands')->delete();
        DB::table('product_categories')->delete();
    }

    private function seedPaymentTerms(): void
    {
        foreach ($this->paymentTerms as $i => $name) {
            PaymentTerm::firstOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $i]
            );
        }
    }

    private function addItems(string $modelClass, string $fk, int $docId, array $products, bool $useBuyPrice = false): array
    {
        $sub = 0;
        $numItems = rand(1, 5);
        for ($j = 0; $j < $numItems; $j++) {
            $prod = $products[array_rand($products)];
            $qty = rand(1, 30);
            $price = $useBuyPrice ? $prod->buy_price : $prod->sell_price;
            $line = round($qty * $price, 2);
            $modelClass::create([
                $fk => $docId,
                'product_id' => $prod->id,
                'description' => $prod->name,
                'sub_description' => $prod->sku,
                'qty' => $qty,
                'unit' => strtolower($prod->unit ?? 'pc'),
                'unit_price' => $price,
                'tax_rate' => 5,
                'discount_pct' => 0,
                'line_total' => $line,
            ]);
            $sub += $line;
        }

        return [round($sub, 2), round($sub * 0.05, 2)];
    }

    private function copyItems(PurchaseOrder $po, SupplierBill $bill): void
    {
        foreach ($po->items as $item) {
            SupplierBillItem::create([
                'supplier_bill_id' => $bill->id,
                'product_id' => $item->product_id,
                'description' => $item->description,
                'sub_description' => $item->sub_description,
                'qty' => $item->qty,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
                'tax_rate' => $item->tax_rate,
                'discount_pct' => $item->discount_pct,
                'line_total' => $item->line_total,
            ]);
        }
    }

    private function recordPayment(string $type, array $party, float $amount, ?int $invoiceId, $date, ?int $billId = null): void
    {
        app(PaymentService::class)->createWithAllocation([
            'type' => $type,
            'customer_id' => $party['customer_id'] ?? null,
            'supplier_id' => $party['supplier_id'] ?? null,
            'currency_id' => Currency::where('code', 'AED')->value('id'),
            'rate' => 3.6724,
            'method' => ['cash', 'bank', 'transfer', 'cheque', 'mobile'][array_rand(['cash', 'bank', 'transfer', 'cheque', 'mobile'])],
            'amount' => $amount,
            'paid_on' => $date ? $date->copy()->addDays(rand(1, 10)) : now(),
            'reference' => strtoupper(substr(uniqid(), -8)),
            'notes' => 'Demo payment',
        ], [[
            'invoice_id' => $invoiceId,
            'supplier_bill_id' => $billId,
            'amount' => $amount,
        ]]);
    }
}
