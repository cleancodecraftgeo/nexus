<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function getByUserId(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->with([
                'items.product',
                'items.variant.attributeValues.attribute',
            ])
            ->latest()
            ->get();
    }
}
