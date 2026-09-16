<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\ProductVariant;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\OutOfStockException;
class OrderService
{
    function __construct(private OrderRepositoryInterface $orderRepo) {}

    public function createOrder(array $data, ?int $userId = null): Order
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
            function () use ($normalizedItems, $userId) {
                $user_id = $userId;
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
                            throw new OutOfStockException;
                        }

                        throw new InsufficientStockException(
                             $item['quantity'],
                            $variant->stock
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
                    'status' => OrderStatus::Pending->value,
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
