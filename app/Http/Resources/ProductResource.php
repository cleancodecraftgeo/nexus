<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Product $resource
 */
class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'brand' => $this->resource->brand?->name,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'thumbnail' => $this->resource->thumbnail,
            'price' => $this->resource->price,
            'description' => $this->resource->description,
            'attributes' => $this->resource->attributes,
            'variants' => $this->whenLoaded('variants', fn() => $this->resource->variants->map(
                function ($variant) {
                    return [
                        'id' => $variant->id,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                        'attributeValues' => $variant->attributeValues->map(fn($atv) => [
                            'id' => $atv->id,
                            'attribute' => $atv->attribute->name,
                            'value' => $atv->value
                        ]),
                    ];
                },

            )),




        ];
    }
}
