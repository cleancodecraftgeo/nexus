<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product')
                    ->tabs([
                        Tab::make('General')
                            ->icon('heroicon-o-cube')
                            ->schema([

                                Section::make('Product Information')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 2,
                                                'xl' => 3,
                                            ]),

                                        TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)->columnSpanFull(),

                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('$'),
                                        FileUpload::make('thumbnail')
                                            ->image()
                                            ->directory('products')
                                            ->disk('public'),

                                        TextInput::make('sku')
                                            // ->default(fn() => 'PRD-' . strtoupper(Str::random(6)))
                                            ->unique(ignoreRecord: true),


                                        MarkdownEditor::make('content'),
                                        RichEditor::make('description')
                                            ->columnSpanFull(),

                                        Textarea::make('short_description')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                        'xl' => 3,
                                    ]),



                                Section::make('Relations')
                                    ->description('Choose category and brand.')
                                    ->icon('heroicon-o-link')
                                    ->aside()
                                    ->schema([
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),

                                        Select::make('unit_id')
                                            ->relationship('unit', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Select Unit')
                                    ]),

                                Section::make('Status')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->default(true),

                                        Toggle::make('is_featured')
                                            ->default(false),
                                    ])
                                    ->columns(2),
                            ]),


                        Tab::make('Relations')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Section::make('Relations')
                                    ->schema([
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),
                                    ])
                                    ->columns(2),
                                Select::make('unit_id')
                                    ->relationship('unit', 'name')
                                    ->searchable()
                                    ->preload()
                            ]),
                    ])
                    ->persistTabInQueryString(),
            ]);
    }
}
