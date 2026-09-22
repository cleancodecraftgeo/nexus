<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{

        public function __construct(private OrderService $orderService)
        {

        }
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

    logger('ORDER USER', [
    'user_id' => $request->user()?->id,
]);

        $order =  $this->orderService->createOrder(
            $request->validated(),
            $request->user()?->id
        );


        return response()->json([
            'message'=>'order Created',
            'order'=>$order
        ],201);
    }

    public function myOrders(Request $request){
        $orders = $this->orderService->getUserOrders(
            $request->user()->id
        );
        return response()->json([
            'orders'=>$orders,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( Order $order)
    {
        Gate::authorize('view', $order);

        $order->load([
            'items.product',
            'items.variant.attributeValues.attribute',
        ]);
        return response()->json([
            'orders'=>$order,
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
