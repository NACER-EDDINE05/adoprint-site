<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
        protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'commande';
    protected static ?string $pluralModelLabel = 'commandes';

        public static function getNavigationGroup(): ?string
        {
            return __('filament.navigation.administration');
        }

        public static function getNavigationLabel(): string
        {
            return __('filament.resources.orders.label');
        }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('N°'),
            TextColumn::make('user.email')->label('Client'),
            TextColumn::make('status')->label('Statut')->badge(),
            TextColumn::make('total')
                ->label('Total')
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ') . ' MAD'),
        ])
            ->emptyStateHeading('Aucune commande pour le moment')
            ->emptyStateDescription('Les commandes passées depuis la boutique apparaîtront ici.')
            ->emptyStateIcon('heroicon-o-receipt-percent');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'));
    }
}
