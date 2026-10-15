<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductOptionResource\Pages;
use App\Models\ProductOption;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ProductOptionResource extends Resource
{
    protected static ?string $model = ProductOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
        protected static ?string $navigationGroup = 'Administration';

        public static function getNavigationGroup(): ?string
        {
            return __('filament.navigation.administration');
        }

        public static function getNavigationLabel(): string
        {
            return __('filament.resources.product_options.label');
        }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')->relationship('product','name')->required(),
            TextInput::make('name')->required()->maxLength(191),
            TextInput::make('type')->required()->maxLength(100),
            Textarea::make('values')->hint('Séparer les valeurs par des virgules')->nullable()->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('name'),
            TextColumn::make('type'),
            TextColumn::make('values')->limit(40),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductOptions::route('/'),
            'create' => Pages\CreateProductOption::route('/create'),
            'edit' => Pages\EditProductOption::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->can('access-admin');
    }
}
