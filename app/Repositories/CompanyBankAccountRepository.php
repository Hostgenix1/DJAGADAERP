<?php

namespace App\Repositories;

use App\Models\CompanyBankAccount;

class CompanyBankAccountRepository extends BaseCrudRepository
{
    public function model(): string
    {
        return CompanyBankAccount::class;
    }
}
