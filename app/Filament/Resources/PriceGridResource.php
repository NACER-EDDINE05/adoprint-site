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

    protected static ?string $modelLabel = 'grille tarifaire';
    protected static ?string $pluralModelLabel = 'grilles tarifaires';

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
            Select::make('product_id')->relationship('product','name')->required()
                ->label('Produit')->searchable()->preload(),
            TextInput::make('name')->label('Nom du palier')->required()->maxLength(191),
            TextInput::make('min_qty')->label('Qté min')->numeric()->required()->rules(['integer','min:0']),
            TextInput::make('max_qty')->label('Qté max')->numeric()->required()->rules(['integer','min:0']),
            TextInput::make('price')->label('Prix (MAD)')->numeric()->required()->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID'),
            TextColumn::make('product.name')->label('Produit'),
            TextColumn::make('name')->label('Palier'),
            TextColumn::make('min_qty')->label('Qté min'),
            TextColumn::make('max_qty')->label('Qté max'),
            TextColumn::make('price')
                ->label('Prix')
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ') . ' MAD'),
        ])
            ->emptyStateHeading('Aucune grille tarifaire pour le moment')
            ->emptyStateDescription('Les grilles définissent le prix par quantité pour chaque produit.')
            ->emptyStateIcon('heroicon-o-table-cells');
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
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
