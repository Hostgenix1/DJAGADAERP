<?php

namespace App\Services;

use App\Models\CompanyBankAccount;

class CompanyBankAccountService
{
    public function query()
    {
        return CompanyBankAccount::with('currency')->latest('id');
    }
}
