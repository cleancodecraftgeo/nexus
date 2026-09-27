<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Filament\Resources\Pages\CreateRecord;


class CreateProduct extends CreateRecord
{
    protected array $translationData = [];

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->translationData = $data['translations'] ?? [];

        unset($data['translations']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Product
    {
        return app(ProductService::class)->create(
            $data,
            $this->translationData,
        );
    }
}
