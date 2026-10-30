<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
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

    /**
     * Statuts connus de la boutique avec leur libellé français. Les statuts
     * inconnus restent affichés tels quels (pas de label inventé).
     */
    public const STATUS_LABELS = [
        'pending'   => 'En attente',
        'quote'     => 'Devis',
        'shipped'   => 'Expédiée',
        'delivered' => 'Livrée',
        'cancelled' => 'Annulée',
    ];

    /** Classes badge du widget « Dernières commandes » (vue Blade). */
    public static function statusLabel(?string $status): string
    {
        return self::STATUS_LABELS[$status] ?? (string) $status;
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            'pending'   => 'bg-amber-100 text-amber-800',
            'quote'     => 'bg-sky-100 text-sky-800',
            'shipped'   => 'bg-primary-100 text-primary-800',
            'delivered' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-700',
        };
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('N°')->sortable(),
            TextColumn::make('client')
                ->label('Client')
                ->state(fn (Order $record) => $record->meta['customer']['nom'] ?? $record->user?->name ?? '—')
                ->description(fn (Order $record) => $record->meta['customer']['email'] ?? $record->user?->email ?? '')
                ->searchable(query: fn ($query, string $search) => $query->orWhere('meta->customer->email', 'like', "%{$search}%")),
            TextColumn::make('status')
                ->label('Statut')
                ->badge()
                ->formatStateUsing(fn ($state) => self::statusLabel($state))
                ->color(fn ($state) => match ($state) {
                    'pending'   => 'warning',
                    'quote'     => 'info',
                    'shipped'   => 'primary',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    default     => 'secondary',
                }),
            TextColumn::make('total')
                ->label('Total')
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', ' ') . ' MAD')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Reçue le')
                ->dateTime('d/m/Y H:i', 'Africa/Casablanca')
                ->sortable(),
        ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options(self::STATUS_LABELS),
            ])
            ->actions([
                //iframe les actions métier utiles : mise à jour du statut sans ouvrir la fiche.
                Action::make('updateStatus')
                    ->label('Mettre à jour le statut')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->form([
                        Select::make('status')
                            ->label('Nouveau statut')
                            ->options(self::STATUS_LABELS)
                            ->default(fn (Order $record) => $record->status)
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);

                        Notification::make()
                            ->title('Statut de la commande n°' . $record->id . ' mis à jour : ' . self::statusLabel($data['status']))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->label('Détail'),
            ])
            ->bulkActions([])
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
