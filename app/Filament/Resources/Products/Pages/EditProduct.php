<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Model;


class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected array $translationData = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record
            ->translations
            ->keyBy('locale');

        $data['translations'] = [
            'en' => [
                'name' => $translations->get('en')?->name,
                'description' => $translations->get('en')?->description,
            ],
            'az' => [
                'name' => $translations->get('az')?->name,
                'description' => $translations->get('az')?->description,
            ],
            'ka' => [
                'name' => $translations->get('ka')?->name,
                'description' => $translations->get('ka')?->description,
            ],
        ];

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translationData = $data['translations'] ?? [];

        unset($data['translations']);

        return $data;
    }


    public function handleRecordUpdate(Model $record, array $data): Product
    {
        return app(ProductService::class)->update(
            $record,
            $data,
            $this->translationData,
        );
    }
}
