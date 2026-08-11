<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderService
{
    function __construct(private OrderRepositoryInterface $orderRepo) {}

    public function createOrder(array $data): Order
    {
        return DB::transaction(
            function () use ($data) {
                $total = 0;

                foreach ($data['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    $total += $product->price * $item['quantity'];
                }

                $order = $this->orderRepo->create([
                    'total' => $total,
                    'status' => 'pending'
                ]);

    // OrderItems block
                foreach ($data['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }

                return $order->load('items');
            }
        );
    }
}
