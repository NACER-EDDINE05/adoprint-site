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

    protected static ?string $modelLabel = 'option de produit';
    protected static ?string $pluralModelLabel = 'options de produits';

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
            Select::make('product_id')->relationship('product','name')->required()
                ->label('Produit')->searchable()->preload(),
            TextInput::make('name')->label('Nom de l’option')->required()->maxLength(191),
            TextInput::make('type')->label('Type (select, radio…)')->required()->maxLength(100),
            Textarea::make('values')->label('Valeurs')->hint('Séparer les valeurs par des virgules')->nullable()->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID'),
            TextColumn::make('product.name')->label('Produit'),
            TextColumn::make('name')->label('Option'),
            TextColumn::make('type')->label('Type'),
            TextColumn::make('values')->label('Valeurs')->limit(40),
        ])
            ->emptyStateHeading('Aucune option pour le moment')
            ->emptyStateDescription('Les options ajoutent des choix au client : format, finition, etc.')
            ->emptyStateIcon('heroicon-o-adjustments-horizontal');
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
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
