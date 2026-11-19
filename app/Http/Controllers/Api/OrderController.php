<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Enums\Currency;

class OrderController extends Controller
{

    public function __construct(private OrderService $orderService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $currencyCode = strtoupper(
            trim((string) $request->header('Accept-Currency', 'USD'))
        );

        $currency = Currency::tryFrom($currencyCode) ?? Currency::USD;

        $order = $this->orderService->createOrder(
            data: $request->validated(),
            userId: $request->user()->id,
            currency: $currency,
        );

        return response()->json([
            'message' => 'Order created.',
            'order' => $order,
        ], 201);
    }

    public function myOrders(Request $request)
    {
        $orders = $this->orderService->getUserOrders(
            $request->user()->id
        );
        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // IDOR Protection
        Gate::authorize('view', $order);

        $order->load([
            'items.product',
            'items.variant.attributeValues.attribute',
        ]);
        return response()->json([
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
