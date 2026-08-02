<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;

class ContactRepository extends BaseCrudRepository implements ContactRepositoryInterface
{
    public function model(): string
    {
        return Contact::class;
    }
}
