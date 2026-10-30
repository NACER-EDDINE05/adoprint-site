<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'produit';
    protected static ?string $pluralModelLabel = 'produits';

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

            Select::make('category_id')->relationship('category','name')
                ->label('Catégorie')
                ->searchable()
                ->preload(),
            TextInput::make('name')->label('Nom')->required(),
            TextInput::make('slug')->label('Slug (URL)')->required(),
            Textarea::make('description')->label('Description')->rows(4),
            FileUpload::make('photo')
                ->label('Photo du produit')
                ->image()
                ->imageEditor()
                ->directory('products')
            ->disk('public')
                ->maxSize(4096)
                ->columnSpanFull(),
            TextInput::make('base_price')->label('Prix de base (MAD)')->numeric(),
            Toggle::make('active')
                ->label('Produit en ligne')
                ->helperText('Désactivé : la fiche produit n\'est plus visible sur la boutique publique.')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('photo')
                ->label('Photo')
                ->circular()
                ->getStateUsing(fn ($record) => $record->photo ? '/storage/' . $record->photo : null)
                ->defaultImageUrl(fn () => 'data:image/svg+xml;base64,' . base64_encode(
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">'
                    . '<rect width="48" height="48" rx="10" fill="#d9f4ff"/>'
                    . '<circle cx="19" cy="19" r="5" fill="#00aeef"/>'
                    . '<path d="M10 36l9-11 7 8 5-6 7 11z" fill="#0089c1"/></svg>'
                )),
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('name')->label('Nom')->searchable(),
            TextColumn::make('category.name')->label('Catégorie'),
            TextColumn::make('base_price')
                ->label('Prix de base')
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ') . ' MAD'),
            // Interrupteur en ligne / hors ligne directement dans la liste.
            ToggleColumn::make('active')
                ->label('En ligne')
                ->afterStateUpdated(fn (Product $record, $state) => Notification::make()
                    ->title($state ? 'Produit remis en ligne.' : 'Produit retiré de la boutique public.')
                    ->success()
                    ->send()),
        ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('active')
                    ->label('Visibilité')
                    ->options(['1' => 'En ligne', '0' => 'Hors ligne']),
            ])
            ->actions([
                // Voir le rendu réel de la fiche produit sur la boutique.
                Action::make('voirSite')
                    ->label('Voir sur le site')
                    ->icon('heroicon-m-globe-alt')
                    ->color('gray')
                    ->url(fn (Product $record) => '/produit/' . $record->slug, shouldOpenInNewTab: true),
            ])
            ->emptyStateHeading('Aucun produit pour le moment')
            ->emptyStateDescription('Cliquez sur « Nouveau produit » pour ajouter le premier article du catalogue.')
            ->emptyStateIcon('heroicon-o-cube');
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
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
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
