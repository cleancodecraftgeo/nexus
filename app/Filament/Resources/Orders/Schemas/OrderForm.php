<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(
                                fn(OrderStatus $status) => [
                                    $status->value => $status->label()
                                ]
                            )
                            ->toArray()
                    )
                    ->required()
            ]);
    }
}
