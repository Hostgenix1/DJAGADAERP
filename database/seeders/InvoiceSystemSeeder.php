<?php

namespace Database\Seeders;

use App\Models\CompanyBankAccount;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class InvoiceSystemSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTaxes();
        $this->seedBankAccounts();
        $this->seedUnits();
        $this->seedProductUnits();
    }

    private function seedUnits(): void
    {
        $existing = \App\Models\Unit::pluck('name')->all();
        $new = array_values(array_diff(config('invoice.units', []), $existing));

        foreach ($new as $i => $name) {
            \App\Models\Unit::firstOrCreate(
                ['name' => $name],
                ['sort_order' => $i, 'is_active' => true]
            );
        }
    }

    private function seedTaxes(): void
    {
        $taxes = [
            ['name' => 'UAE VAT', 'rate' => 5.000, 'kind' => 'sales', 'is_default' => true, 'is_active' => true],
            ['name' => 'Netherlands VAT', 'rate' => 21.000, 'kind' => 'sales', 'is_default' => false, 'is_active' => true],
            ['name' => 'Zero Rated', 'rate' => 0.000, 'kind' => 'sales', 'is_default' => false, 'is_active' => true],
        ];

        foreach ($taxes as $tax) {
            Tax::firstOrCreate(['name' => $tax['name']], $tax);
        }
    }

    private function seedBankAccounts(): void
    {
        $currencies = Currency::all()->keyBy('code');

        $accounts = [
            [
                'bank_name' => 'Mashreq Bank',
                'account_name' => 'DJAGADA FZ-LLC',
                'account_number' => '019101860955',
                'iban' => 'AE970330000019101860955',
                'swift_code' => 'BOMLAEAD',
                'bank_address' => 'Burj Al Shams, Burj Khalifa Community, Plot No.345, Downtown Umniyati St, Al Asayel St, Dubai, UAE',
                'currency_code' => 'AED',
                'is_default' => true,
            ],
            [
                'bank_name' => 'Mashreq Bank',
                'account_name' => 'DJAGADA FZ-LLC',
                'account_number' => '019101860960',
                'iban' => 'AE970330000019101860960',
                'swift_code' => 'BOMLAEAD',
                'bank_address' => 'Burj Al Shams, Burj Khalifa Community, Plot No.345, Downtown Umniyati St, Al Asayel St, Dubai, UAE',
                'currency_code' => 'USD',
                'is_default' => false,
            ],
            [
                'bank_name' => 'Mashreq Bank',
                'account_name' => 'DJAGADA FZ-LLC',
                'account_number' => '019101860965',
                'iban' => 'AE970330000019101860965',
                'swift_code' => 'BOMLAEAD',
                'bank_address' => 'Burj Al Shams, Burj Khalifa Community, Plot No.345, Downtown Umniyati St, Al Asayel St, Dubai, UAE',
                'currency_code' => 'EUR',
                'is_default' => false,
            ],
        ];

        foreach ($accounts as $acc) {
            $currency = $currencies->get($acc['currency_code']);
            if (!$currency) {
                continue;
            }

            $data = $acc;
            unset($data['currency_code']);
            $data['currency_id'] = $currency->id;

            CompanyBankAccount::firstOrCreate(
                ['iban' => $acc['iban']],
                $data
            );
        }
    }

    private function seedProductUnits(): void
    {
        $unitMap = [
            'Samsung 24" Monitor' => 'Unit',
            'HP LaserJet Pro Printer' => 'Unit',
            'Canon Ink Cartridge Pack' => 'Box',
            'A4 Copy Paper (Ream)' => 'Carton',
            'Ergonomic Office Chair' => 'Unit',
        ];

        foreach ($unitMap as $name => $unit) {
            Product::where('name', $name)->update(['unit' => $unit]);
        }
    }
}
