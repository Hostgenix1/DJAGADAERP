<?php

namespace App\Services;

use App\Models\Shipment;

class ShipmentService
{
    public function query()
    {
        return Shipment::with(['customer', 'order', 'invoice'])->latest('id');
    }
}
