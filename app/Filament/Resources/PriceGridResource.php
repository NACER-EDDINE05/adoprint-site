<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceGridResource\Pages;
use App\Models\PriceGrid;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PriceGridResource extends Resource
{
    protected static ?string $model = PriceGrid::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
        protected static ?string $navigationGroup = 'Administration';

        public static function getNavigationGroup(): ?string
        {
            return __('filament.navigation.administration');
        }

        public static function getNavigationLabel(): string
        {
            return __('filament.resources.price_grids.label');
        }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')->relationship('product','name')->required(),
            TextInput::make('name')->required()->maxLength(191),
            TextInput::make('min_qty')->label('Min qty')->numeric()->required()->rules(['integer','min:0']),
            TextInput::make('max_qty')->label('Max qty')->numeric()->required()->rules(['integer','min:0']),
            TextInput::make('price')->numeric()->required()->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('name'),
            TextColumn::make('min_qty')->label('Min qty'),
            TextColumn::make('max_qty')->label('Max qty'),
            TextColumn::make('price')->money('MAD'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceGrids::route('/'),
            'create' => Pages\CreatePriceGrid::route('/create'),
            'edit' => Pages\EditPriceGrid::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->can('access-admin');
    }
}
