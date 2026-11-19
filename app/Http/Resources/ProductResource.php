<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Currency;
use App\Services\CurrencyService;

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
        $locale = $request->header('Accept-Language', 'en');
        $currencyCode = $request->header('Accept-Currency', 'USD');
        $currency = Currency::tryFrom($currencyCode) ?? Currency::USD;
        $currencyService = app(CurrencyService::class);

        $translation = $this->resource->translations
            ->firstWhere('locale', $locale);
        return [
            'id' => $this->resource->id,
            'brand' => $this->resource->brand?->name,
            'name' => $translation?->name ?? $this->resource->name,
            'slug' => $this->resource->slug,
            'thumbnail' => $this->resource->thumbnail,
            'price' => $currencyService->convert(
                (float) $this->resource->price,
                Currency::GEL,
                $currency
            ),
            'currency' => $currency->value,
            'description' => $translation?->description ?? $this->resource->description,
            'attributes' => $this->resource->attributes,

            'variants' => $this->whenLoaded('variants', fn() => $this->resource->variants->map(
                function ($variant) use ($currencyService, $currency) {
                    return [
                        'id' => $variant->id,
                        'price' => $currencyService->convert(
                            (float) $variant->price,
                            Currency::GEL,
                            $currency
                        ),
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
