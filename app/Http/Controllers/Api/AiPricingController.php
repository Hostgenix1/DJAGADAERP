<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SellingPrice;
use Illuminate\Http\Request;

/**
 * Future AI Sales Assistant endpoint.
 *
 * Resolution order: Customer-Specific Approved Price -> General Approved Price.
 * - Only prices with approved_for_ai = true
 * - Only approved status and within validity (expired never returned)
 * - Whitelisted fields only: supplier costs, margins and any confidential
 *   financial data are structurally excluded from the response.
 */
class AiPricingController extends Controller
{
    public function prices(Request $request)
    {
        if (! auth()->user()->can('ai-read-prices')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'customer_id' => 'nullable|integer',
            'customer_name' => 'nullable|string|max:255',
            'product_id' => 'nullable|integer',
            'product_name' => 'nullable|string|max:255',
            'min_qty' => 'nullable|numeric|min:0',
        ]);

        $customerId = $data['customer_id']
            ?? (isset($data['customer_name']) ? \App\Models\Customer::where('company_name', $data['customer_name'])->value('id') : null);

        $productId = $data['product_id']
            ?? (isset($data['product_name']) ? \App\Models\Product::where('name', $data['product_name'])->value('id') : null);

        $prices = SellingPrice::query()
            ->where('approved_for_ai', true)
            ->currentlyApproved()
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($customerId, fn ($q) => $q->where(fn ($w) => $w->whereNull('customer_id')->orWhere('customer_id', $customerId)))
            ->with(['product:id,name', 'currency:id,code,symbol'])
            ->orderByRaw('CASE WHEN customer_id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            // Whitelist: customer-specific approved first, general next.
            ->sortBy(fn (SellingPrice $p) => $p->customer_id === null ? 1 : 0)
            ->values()
            ->map(fn (SellingPrice $p) => [
                'product' => $p->product?->name,
                'packaging' => $p->packaging,
                'selling_price' => (float) $p->selling_price,
                'currency' => $p->currency?->code,
                'destination' => $p->destination,
                'incoterm' => $p->incoterm ? strtoupper($p->incoterm) : null,
                'min_qty' => $p->min_qty !== null ? (float) $p->min_qty : null,
                'valid_until' => $p->valid_until?->toDateString(),
                'customer_specific' => $p->customer_id !== null,
            ])
            ->values();

        return response()->json([
            'data' => $prices,
            'note' => $prices->isEmpty() ? 'No valid approved price available. Do not quote an expired or invented price.' : null,
        ]);
    }
}
