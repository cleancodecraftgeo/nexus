<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\ProductVariant;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
class OrderService
{
    function __construct(private OrderRepositoryInterface $orderRepo) {}

    public function createOrder(array $data): Order
    {
        $normalizedItems = collect($data['items'])
            ->groupBy('variant_id')
            ->map(function ($items) {
                return [
                    'product_id' => $items->first()['product_id'],
                    'variant_id' => $items->first()['variant_id'],
                    'quantity' => $items->sum('quantity')
                ];
            })->values();



        return DB::transaction(
            function () use ($normalizedItems) {
                $total = 0;
                $resolvedItems = [];


                foreach ($normalizedItems as $item) {
                    $variant = ProductVariant::whereKey($item['variant_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($variant->product_id !== $item['product_id']) {
                        throw new HttpException(422,'Variant does not belong to this product.');
                    }

                    if ($item['quantity'] > $variant->stock) {
                        if ($variant->stock === 0) {
                            throw new ConflictHttpException("This product is out of stock.");
                        }

                        throw new ConflictHttpException(

                            "Insufficient stock. Requested: {$item['quantity']}, Available: {$variant->stock}."
                        );
                    }

                    $total += $variant->price * $item['quantity'];

                    $resolvedItems[] = [
                        'variant' => $variant,
                        'quantity' => $item['quantity'],
                    ];
                }

                $order = $this->orderRepo->create([
                    'total' => $total,
                    'status' => 'pending'
                ]);

                // OrderItems block
                foreach ($resolvedItems as $resolvedItem) {
                    $variant = $resolvedItem['variant'];
                    $quantity = $resolvedItem['quantity'];

                    $order->items()->create([
                        'product_id' => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $quantity,
                        'price' => $variant->price,
                    ]);
                    $variant->decrement('stock', $quantity);
                }

                return $order->load('items');
            }
        );
    }
}
