<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Product\Criteria\CategoryCriteria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

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
                'attributes.values',
                'variants.attributeValues',
                'variants.attributeValues.attribute',
            ])
            ->where('slug', $slug)
            ->first();
    }
}
