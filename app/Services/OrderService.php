<?php

namespace App\Services;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\OutOfStockException;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepo,
        private CurrencyService $currencyService,
    ) {}

    public function createOrder(
        array $data,
        ?int $userId = null,
        Currency $currency = Currency::USD,
    ): Order {

        foreach ($data['items'] as $item) {
            $belongsToProduct = ProductVariant::query()
                ->whereKey($item['variant_id'])
                ->where('product_id', $item['product_id'])
                ->exists();

            if (! $belongsToProduct) {
                throw new HttpException(
                    422,
                    'Variant does not belong to this product.',
                );
            }
        }

        $normalizedItems = collect($data['items'])
            ->groupBy('variant_id')
            ->map(function ($items) {
                return [
                    'product_id' => $items->first()['product_id'],
                    'variant_id' => $items->first()['variant_id'],
                    'quantity' => $items->sum('quantity')
                ];
            })->values();


        $exchangeRate = round(
            $this->currencyService->getRate(
                Currency::USD,
                $currency,
            ),
            4
        );


        return DB::transaction(
            function () use (
                $normalizedItems,
                $userId,
                $data,
                $currency,
                $exchangeRate
            ) {

                $total = 0;
                $resolvedItems = [];


                foreach ($normalizedItems as $item) {
                    $variant = ProductVariant::whereKey($item['variant_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($variant->product_id !== $item['product_id']) {
                        throw new HttpException(422, 'Variant does not belong to this product.');
                    }

                    if ($item['quantity'] > $variant->stock) {
                        if ($variant->stock === 0) {
                            throw new OutOfStockException();
                        }

                        throw new InsufficientStockException(
                            $item['quantity'],
                            $variant->stock
                        );
                    }

                    $unitPrice = round(
                        (float) $variant->price * $exchangeRate,
                        2,
                    );
                    $total += $unitPrice * $item['quantity'];

                    $resolvedItems[] = [
                        'variant' => $variant,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                    ];
                }

                $order = $this->orderRepo->create([
                    'user_id' => $userId,

                    'total' => round($total, 2),
                    'status' => OrderStatus::Pending->value,

                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'city' => $data['city'],
                    'address' => $data['address'],
                    'postal_code' => $data['postal_code'] ?? null,
                    'notes' => $data['notes'] ?? null,

                    'currency_code' => $currency->value,
                    'exchange_rate' => $exchangeRate,

                ]);

                // OrderItems block
                foreach ($resolvedItems as $resolvedItem) {
                    $variant = $resolvedItem['variant'];
                    $quantity = $resolvedItem['quantity'];

                    $order->items()->create([
                        'product_id' => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $quantity,
                        'price' => $resolvedItem['unit_price'],
                    ]);
                    $variant->decrement('stock', $quantity);
                }

                return $order->load('items');
            }
        );
    }
    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepo->getByUserId($userId);
    }
}
