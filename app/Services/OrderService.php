<?php
namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function createWithItems(array $data, array $items): Order
    {
        $data['number'] = Order::nextNumber();
        $order = Order::create($data);
        foreach ($items as $item) {
            $order->items()->create($item);
        }
        $order->recalculate();
        return $order;
    }

    public function updateWithItems(Order $order, array $data, array $items): Order
    {
        $order->update($data);
        $order->items()->delete();
        foreach ($items as $item) {
            $order->items()->create($item);
        }
        $order->recalculate();
        return $order;
    }

    public function query()
    {
        return Order::with('customer')->latest('id');
    }
}
