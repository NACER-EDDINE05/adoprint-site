<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PriceService;

class PriceController extends Controller
{
    protected PriceService $service;

    public function __construct(PriceService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $productId = (int) $request->query('product_id');
        $qty = (int) $request->query('qty');

        $price = $this->service->getPriceForQuantity($productId, $qty);

        return response()->json(['price' => $price]);
    }
}
