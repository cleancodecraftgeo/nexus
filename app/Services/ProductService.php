<?php

namespace App\Services;

use App\Contracts\InventoryServiceInterface;
use App\Events\ProductCreated;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    //    public function create(array $data): Product
    // {
    //     return DB::transaction(function () use ($data) {

    //         $product = Product::create($data);

    //         throw new \Exception('Test Transaction');

    //         ProductVariant::create([
    //             'product_id' => $product->id,
    //             'sku' => $product->sku,
    //             'price' => $product->price,
    //             'stock' => 0,
    //             'is_active' => true,
    //             'is_default' => true,
    //         ]);

    //         return $product;
    //     });
    // }

    public function __construct(
        private ProductRepositoryInterface $productRepo,
        private InventoryServiceInterface  $inventoryService,

    ) {


        logger('🛒 ProductService yaradıldı');
    }


    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {


            $product = $this->createProduct($data);

            $this->createDefaultVariant($product);

            ProductCreated::dispatch($product);

            return $product;
        });
    }





    private function generateSku(): string
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(6));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    private function generateSlug(string $name): string
    {
        $slug =  Str::slug($name);
        $original = $slug;
        $count = 2;

        while ($this->productRepo->slugExists($slug))
            {
                $slug = $original . '-' . $count;
                $count++;
            }
            return $slug;
    }




    private function createProduct(array $data): Product
    {
        $data['sku'] = $this->generateSku();
        $data['slug'] = $this->generateSlug($data['name']);

        return $this->productRepo->create($data);
    }



    private function createDefaultVariant(Product $product): void
    {


        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => 0,
            'is_active' => true,
            'is_featured' => true,
        ]);
    }

    public function index(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->productRepo->paginate();
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->productRepo->findBySlug($slug);
    }
}
