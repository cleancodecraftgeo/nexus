<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

    public function __construct(Product $product)
    {
        parent::__construct($product);
    }


    public function slugExists(string $slug): bool
    {
        return $this->model->where('slug', $slug)->exists();
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model
            ->with([
                'brand',
                'translations',
                'attributes.values',
                'variants.attributeValues',
                'variants.attributeValues.attribute',

            ])
            ->where('slug', $slug)
            ->first();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->applyCriteria()
            ->with([
                'brand',
                'translations',
                'attributes.values',
                'variants.attributeValues.attribute',
            ])
            ->paginate($perPage);
    }
}
