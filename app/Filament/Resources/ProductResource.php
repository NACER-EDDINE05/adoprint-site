<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Live price preview (Livewire component rendered directly by Blade)
            Placeholder::make('price_preview')
                ->label('Aperçu du prix')
                ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        '@livewire(\'price-preview\', [\'productId\' => $productId])',
                        ['productId' => $record?->id]
                    )
                )),

            Select::make('category_id')->relationship('category','name'),
            TextInput::make('name')->required(),
            TextInput::make('slug')->required(),
            Textarea::make('description'),
            FileUpload::make('photo')
                ->label('Photo du produit')
                ->image()
                ->imageEditor()
                ->directory('products')
            ->disk('public')
                ->maxSize(4096)
                ->columnSpanFull(),
            TextInput::make('base_price')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('photo')
                ->label('Photo')
                ->circular()
                ->getStateUsing(fn ($record) => $record->photo ? '/storage/' . $record->photo : null),
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('category.name')->label('Category'),
            TextColumn::make('base_price')->money('MAD'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->can('access-admin');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.products.label');
    }
}
